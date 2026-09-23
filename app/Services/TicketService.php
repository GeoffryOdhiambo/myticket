<?php

namespace App\Services;

use App\Jobs\SendTicketNotifications;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

            /** @var Event $event */
            $event = Event::whereKey($order->event_id)->lockForUpdate()->first();
            $tickets = collect();

            foreach ($order->items()->with('ticketType')->get() as $item) {
                /** @var TicketType $ticketType */
                $ticketType = TicketType::whereKey($item->ticket_type_id)->lockForUpdate()->first();

                // The order was only checked against available inventory at
                // checkout time; a concurrent order for the same limited
                // ticket type could have sold out in between. The customer
                // has already paid by this point, so we still honor it —
                // refusing a ticket someone paid for isn't an option — but
                // this needs an organizer's attention to sort out capacity.
                if (! $ticketType->isUnlimited() && $ticketType->quantity_sold + $item->quantity > $ticketType->quantity) {
                    Log::critical('[TicketService] Oversold ticket type', [
                        'ticket_type_id' => $ticketType->id,
                        'ticket_type_name' => $ticketType->name,
                        'order_number' => $order->order_number,
                        'quantity' => $ticketType->quantity,
                        'quantity_sold_before' => $ticketType->quantity_sold,
                        'quantity_requested' => $item->quantity,
                    ]);
                }

                for ($i = 0; $i < $item->quantity; $i++) {
                    $event->increment('ticket_sequence');

                    $tickets->push($item->tickets()->create([
                        'ticket_number' => "TIKO-{$event->ticket_prefix}".str_pad((string) $event->ticket_sequence, 3, '0', STR_PAD_LEFT),
                    ]));
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
