<?php

namespace App\Services;

use App\Jobs\SendTicketNotifications;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TicketService
{
    /**
     * Generate one ticket per purchased unit for a paid order, incrementing
     * ticket type inventory. Safe to call more than once for the same order —
     * tickets are only generated the first time.
     */
    public function generateTicketsForOrder(Order $order): Collection
    {
        return DB::transaction(function () use ($order) {
            $order->refresh();

            if (Ticket::whereIn('order_item_id', $order->items()->pluck('id'))->exists()) {
                return collect();
            }

            $tickets = collect();

            foreach ($order->items()->with('ticketType')->get() as $item) {
                /** @var TicketType $ticketType */
                $ticketType = TicketType::whereKey($item->ticket_type_id)->lockForUpdate()->first();

                for ($i = 0; $i < $item->quantity; $i++) {
                    $tickets->push($item->tickets()->create([]));
                }

                $ticketType->increment('quantity_sold', $item->quantity);
            }

            dispatch(new SendTicketNotifications($order->id));

            return $tickets;
        });
    }

    public function checkIn(Ticket $ticket, string $checkedInBy): void
    {
        $ticket->checkIn($checkedInBy);
    }
}
