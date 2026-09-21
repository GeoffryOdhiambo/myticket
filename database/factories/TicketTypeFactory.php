<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketTypeFactory extends Factory
{
    public const TIERS = [
        'Regular' => ['price' => [500, 1000], 'desc' => 'Standard entry to the event.'],
        'VIP' => ['price' => [1500, 3000], 'desc' => 'Priority entry with access to the VIP area.'],
        'VVIP' => ['price' => [3500, 6000], 'desc' => 'Premium experience with the best seating and perks.'],
        'Couples' => ['price' => [900, 1800], 'desc' => 'Entry for two guests.'],
        'Early Bird' => ['price' => [350, 700], 'desc' => 'Limited discounted tickets for early buyers.'],
    ];

    public function definition(): array
    {
        $tier = fake()->randomElement(array_keys(self::TIERS));
        $config = self::TIERS[$tier];

        return [
            'event_id' => Event::factory(),
            'name' => $tier,
            'description' => $config['desc'],
            'price' => fake()->numberBetween(...$config['price']),
            'quantity' => fake()->randomElement([50, 100, 150, 200, null]),
            'quantity_sold' => 0,
            'status' => 'active',
        ];
    }
}
