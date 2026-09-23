<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Event;
use App\Models\Order;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CheckoutController extends Controller
{
    public function create(Request $request, Event $event): View|Response
    {
        if (! $event->isPublished()) {
            return response()->view('events.unavailable', [], 404);
        }

        $selection = $this->resolveSelection($event, (array) $request->query('items', []));

        if ($selection['quantity'] === 0) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Please select at least one ticket to continue.');
        }

        if ($selection['error']) {
            return redirect()->route('events.show', $event)->with('error', $selection['error']);
        }

        return view('checkout.create', [
            'event' => $event,
            'lines' => $selection['lines'],
            'subtotal' => $selection['subtotal'],
        ]);
    }

    public function store(CheckoutRequest $request, Event $event): RedirectResponse
    {
        if (! $event->isPublished()) {
            return redirect()->route('events.index')->with('error', 'This event is no longer available.');
        }

        $selection = $this->resolveSelection($event, (array) $request->input('items', []));

        if ($selection['quantity'] === 0 || $selection['error']) {
            return redirect()->route('events.show', $event)
                ->with('error', $selection['error'] ?? 'Please select at least one ticket to continue.');
        }

        $order = DB::transaction(function () use ($request, $event, $selection) {
            $order = Order::create([
                'event_id' => $event->id,
                'customer_name' => $request->string('customer_name'),
                'customer_whatsapp' => $request->string('customer_whatsapp'),
                'customer_email' => $request->string('customer_email'),
                'subtotal' => $selection['subtotal'],
                'total' => $selection['subtotal'],
                'status' => 'pending',
            ]);

            foreach ($selection['lines'] as $line) {
                $order->items()->create([
                    'ticket_type_id' => $line['type']->id,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['type']->price,
                    'subtotal' => $line['subtotal'],
                ]);
            }

            return $order;
        });

        try {
            app(PaymentManager::class)->driver()->initiate($order);
        } catch (Throwable $e) {
            Log::error('[Checkout] Failed to initiate payment', ['order' => $order->order_number, 'error' => $e->getMessage()]);
            $order->update(['status' => 'failed']);

            return redirect()->route('events.show', $event)
                ->with('error', 'We could not start your payment. Please try again in a moment.');
        }

        return redirect()->route('checkout.pending', $order);
    }

    public function pending(Order $order): View
    {
        $order->load(['event', 'items.ticketType', 'items.tickets', 'payment']);

        return view('checkout.pending', [
            'order' => $order,
            'isManual' => $order->payment?->provider === 'manual',
        ]);
    }

    public function status(Order $order): JsonResponse
    {
        return response()->json(['status' => $order->status]);
    }

    public function simulate(Order $order): RedirectResponse
    {
        abort_if(app()->environment('production'), 404);
        abort_unless($order->payment?->provider === 'manual', 404);

        if ($order->status === 'pending') {
            app(PaymentManager::class)->driver('manual')->handleCallback(['order_number' => $order->order_number]);
        }

        return redirect()->route('checkout.pending', $order);
    }

    /**
     * @return array{lines: array, subtotal: int, quantity: int, error: ?string}
     */
    private function resolveSelection(Event $event, array $items): array
    {
        $ticketTypes = $event->ticketTypes()->where('status', 'active')->get()->keyBy('id');
        $lines = [];
        $subtotal = 0;
        $quantity = 0;

        foreach ($items as $ticketTypeId => $qty) {
            $qty = (int) $qty;

            if ($qty <= 0) {
                continue;
            }

            $type = $ticketTypes->get((int) $ticketTypeId);

            if (! $type) {
                continue;
            }

            if (! $type->canPurchase($qty)) {
                return ['lines' => [], 'subtotal' => 0, 'quantity' => 0, 'error' => "\"{$type->name}\" doesn't have enough tickets available."];
            }

            $lineSubtotal = $type->price * $qty;

            $lines[] = ['type' => $type, 'quantity' => $qty, 'subtotal' => $lineSubtotal];
            $subtotal += $lineSubtotal;
            $quantity += $qty;
        }

        return ['lines' => $lines, 'subtotal' => $subtotal, 'quantity' => $quantity, 'error' => null];
    }
}
