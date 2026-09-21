<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\TicketType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $organizer = Auth::guard('organizer')->user();
        $events = $organizer->events()->orderBy('name')->get();

        $items = OrderItem::query()
            ->whereHas('order', fn ($q) => $q->whereIn('event_id', $events->pluck('id')))
            ->with(['order', 'ticketType', 'tickets'])
            ->when($request->filled('event'), fn ($q) => $q->whereHas('order', fn ($q) => $q->where('event_id', $request->integer('event'))))
            ->when($request->filled('ticket_type'), fn ($q) => $q->where('ticket_type_id', $request->integer('ticket_type')))
            ->when($request->filled('payment_status'), fn ($q) => $q->whereHas('order', fn ($q) => $q->where('status', $request->string('payment_status'))))
            ->when($request->filled('check_in'), function ($q) use ($request) {
                $checkedIn = $request->string('check_in') === 'checked_in';
                $q->where(function ($q) use ($checkedIn) {
                    $checkedIn
                        ? $q->whereHas('tickets', fn ($q) => $q->where('checked_in', true))
                        : $q->whereDoesntHave('tickets', fn ($q) => $q->where('checked_in', true));
                });
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = $request->string('q');
                $q->whereHas('order', fn ($q) => $q
                    ->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_whatsapp', 'like', "%{$search}%")
                    ->orWhere('order_number', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('organizer.tickets.index', [
            'items' => $items,
            'events' => $events,
            'ticketTypes' => TicketType::whereIn('event_id', $events->pluck('id'))->with('event')->get(),
            'filters' => $request->only(['event', 'ticket_type', 'payment_status', 'check_in', 'q']),
        ]);
    }
}
