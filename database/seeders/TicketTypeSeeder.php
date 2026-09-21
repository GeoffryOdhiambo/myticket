<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = [
            ['name' => 'Early Bird', 'price' => 500, 'quantity' => 50, 'description' => 'Limited discounted tickets for early buyers.'],
            ['name' => 'Regular', 'price' => 1000, 'quantity' => 200, 'description' => 'Standard entry to the event.'],
            ['name' => 'VIP', 'price' => 2500, 'quantity' => 80, 'description' => 'Priority entry with access to the VIP area.'],
            ['name' => 'Couples', 'price' => 1800, 'quantity' => 60, 'description' => 'Entry for two guests.'],
        ];

        Event::all()->each(function (Event $event) use ($tiers) {
            if ($event->ticketTypes()->exists()) {
                return;
            }

            foreach (array_slice($tiers, 0, fake()->numberBetween(2, 4)) as $tier) {
                $event->ticketTypes()->create([
                    'name' => $tier['name'],
                    'description' => $tier['description'],
                    'price' => $tier['price'],
                    'quantity' => $tier['quantity'],
                    'quantity_sold' => 0,
                    'status' => 'active',
                ]);
            }
        });
    }
}
