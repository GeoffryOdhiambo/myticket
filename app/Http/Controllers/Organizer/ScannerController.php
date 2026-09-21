<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\QrCodeService;
use App\Services\TicketService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScannerController extends Controller
{
    public function index(): View
    {
        $organizer = Auth::guard('organizer')->user();
        $eventIds = $organizer->events()->pluck('id');

        $sold = Ticket::whereHas('orderItem.order', fn ($q) => $q->whereIn('event_id', $eventIds)->where('status', 'paid'))->count();
        $checkedIn = Ticket::whereHas('orderItem.order', fn ($q) => $q->whereIn('event_id', $eventIds)->where('status', 'paid'))->where('checked_in', true)->count();

        return view('organizer.scanner', [
            'sold' => $sold,
            'checkedIn' => $checkedIn,
            'remaining' => $sold - $checkedIn,
        ]);
    }

    public function verify(Request $request, QrCodeService $qr): JsonResponse
    {
        $request->validate(['payload' => ['required', 'string']]);

        $ticket = $qr->verify($request->string('payload'));
        $organizer = Auth::guard('organizer')->user();

        if (! $ticket || $ticket->orderItem->order->event->organizer_id !== $organizer->id) {
            return response()->json(['status' => 'invalid']);
        }

        $ticket->load('orderItem.order.event', 'orderItem.ticketType');

        if ($ticket->checked_in) {
            return response()->json([
                'status' => 'used',
                'ticket' => $this->ticketPayload($ticket),
            ]);
        }

        return response()->json([
            'status' => 'valid',
            'ticket' => $this->ticketPayload($ticket),
        ]);
    }

    public function checkIn(Ticket $ticket, TicketService $tickets): JsonResponse
    {
        $organizer = Auth::guard('organizer')->user();
        $ticket->load('orderItem.order.event', 'orderItem.ticketType');

        if ($ticket->orderItem->order->event->organizer_id !== $organizer->id) {
            return response()->json(['status' => 'invalid'], 404);
        }

        $checkedIn = DB::transaction(function () use ($ticket, $tickets, $organizer) {
            $locked = Ticket::whereKey($ticket->id)->lockForUpdate()->first();

            if ($locked->checked_in) {
                return false;
            }

            $tickets->checkIn($locked, $organizer->name);

            return true;
        });

        if (! $checkedIn) {
            $ticket->refresh();

            return response()->json([
                'status' => 'used',
                'ticket' => $this->ticketPayload($ticket),
            ]);
        }

        $ticket->refresh();

        return response()->json([
            'status' => 'checked_in',
            'ticket' => $this->ticketPayload($ticket),
        ]);
    }

    private function ticketPayload(Ticket $ticket): array
    {
        $order = $ticket->orderItem->order;

        return [
            'ticket_number' => $ticket->ticket_number,
            'customer_name' => $order->customer_name,
            'ticket_type' => $ticket->orderItem->ticketType->name,
            'event_name' => $order->event->name,
            'checked_in' => $ticket->checked_in,
            'checked_in_at' => $ticket->checked_in_at?->format('d M Y, g:i A'),
            'checked_in_by' => $ticket->checked_in_by,
        ];
    }
}
