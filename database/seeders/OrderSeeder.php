<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Event::with('ticketTypes')->published()->take(6)->get()->each(function (Event $event) {
            $ticketTypes = $event->ticketTypes;

            if ($ticketTypes->isEmpty()) {
                return;
            }

            foreach (range(1, fake()->numberBetween(2, 4)) as $i) {
                $ticketType = $ticketTypes->random();
                $quantity = fake()->numberBetween(1, 3);
                $subtotal = $ticketType->price * $quantity;

                $order = Order::create([
                    'event_id' => $event->id,
                    'customer_name' => fake()->name(),
                    'customer_whatsapp' => '+2547'.fake()->numerify('########'),
                    'customer_email' => fake()->safeEmail(),
                    'subtotal' => $subtotal,
                    'total' => $subtotal,
                    'status' => 'paid',
                    'paid_at' => now()->subDays(fake()->numberBetween(0, 10)),
                ]);

                $item = $order->items()->create([
                    'ticket_type_id' => $ticketType->id,
                    'quantity' => $quantity,
                    'unit_price' => $ticketType->price,
                    'subtotal' => $subtotal,
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'provider' => 'manual',
                    'amount' => $subtotal,
                    'status' => 'paid',
                    'provider_reference' => strtoupper(fake()->bothify('MANUAL-??####')),
                ]);

                for ($t = 0; $t < $quantity; $t++) {
                    $checkedIn = fake()->boolean(30);
                    $event->increment('ticket_sequence');

                    Ticket::create([
                        'order_item_id' => $item->id,
                        'ticket_number' => "TIKO-{$event->ticket_prefix}".str_pad((string) $event->ticket_sequence, 3, '0', STR_PAD_LEFT),
                        'checked_in' => $checkedIn,
                        'checked_in_at' => $checkedIn ? now()->subDays(fake()->numberBetween(0, 3)) : null,
                        'checked_in_by' => $checkedIn ? $event->organizer->name : null,
                    ]);
                }

                $ticketType->increment('quantity_sold', $quantity);
            }
        });
    }
}
