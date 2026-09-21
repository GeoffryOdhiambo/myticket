<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Services\Payments\PaymentManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_manual_callback_does_not_generate_duplicate_tickets(): void
    {
        $ticketType = TicketType::factory()->create(['price' => 1000, 'quantity' => 10, 'quantity_sold' => 0]);

        $order = Order::factory()->pending()->create([
            'event_id' => $ticketType->event_id,
            'subtotal' => 2000,
            'total' => 2000,
        ]);

        $order->items()->create([
            'ticket_type_id' => $ticketType->id,
            'quantity' => 2,
            'unit_price' => 1000,
            'subtotal' => 2000,
        ]);

        $payments = app(PaymentManager::class)->driver('manual');
        $payments->initiate($order);

        // Simulate the payment callback firing twice (e.g. a retried webhook).
        $payments->handleCallback(['order_number' => $order->order_number]);
        $payments->handleCallback(['order_number' => $order->order_number]);

        $this->assertSame(2, Ticket::whereHas('orderItem', fn ($q) => $q->where('order_id', $order->id))->count());
        $this->assertSame(2, $ticketType->fresh()->quantity_sold);
        $this->assertSame('paid', $order->fresh()->status);
    }
}
