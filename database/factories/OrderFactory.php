<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'customer_name' => fake()->name(),
            'customer_whatsapp' => '+2547' . fake()->numerify('########'),
            'customer_email' => fake()->safeEmail(),
            'subtotal' => 0,
            'total' => 0,
            'status' => 'paid',
            'paid_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pending', 'paid_at' => null]);
    }
}
