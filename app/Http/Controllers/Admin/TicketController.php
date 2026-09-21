<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $tickets = Ticket::query()
            ->with(['orderItem.order.event.organizer', 'orderItem.ticketType'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = $request->string('q');
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhereHas('orderItem.order', fn ($q) => $q
                        ->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('order_number', 'like', "%{$search}%"));
            })
            ->when($request->filled('check_in'), function ($q) use ($request) {
                $q->where('checked_in', $request->string('check_in') === 'checked_in');
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'filters' => $request->only(['q', 'check_in']),
        ]);
    }
}
