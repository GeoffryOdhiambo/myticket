<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 3);
        $unitPrice = fake()->numberBetween(500, 3000);

        return [
            'order_id' => Order::factory(),
            'ticket_type_id' => TicketType::factory(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $quantity * $unitPrice,
        ];
    }
}
