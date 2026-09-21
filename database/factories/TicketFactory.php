<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_item_id' => OrderItem::factory(),
            'checked_in' => false,
        ];
    }

    public function checkedIn(): static
    {
        return $this->state([
            'checked_in' => true,
            'checked_in_at' => now(),
            'checked_in_by' => fake()->name(),
        ]);
    }
}
