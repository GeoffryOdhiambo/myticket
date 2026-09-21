<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'provider' => 'manual',
            'amount' => 0,
            'status' => 'paid',
            'provider_reference' => strtoupper(fake()->bothify('??######')),
        ];
    }
}
