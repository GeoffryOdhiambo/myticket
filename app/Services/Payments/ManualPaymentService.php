<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\Payment;

/**
 * Local/dev payment driver used while no live gateway is configured.
 * Creates a pending payment record and exposes a "Simulate Payment"
 * action (see CheckoutController::simulate) that confirms it the same
 * way a real provider callback would.
 */
class ManualPaymentService extends AbstractPaymentService
{
    public function initiate(Order $order): void
    {
        $order->payment()->firstOrCreate([], [
            'provider' => 'manual',
            'amount' => $order->total,
            'status' => 'pending',
        ]);
    }

    public function handleCallback(array $payload): void
    {
        $order = Order::where('order_number', $payload['order_number'])->firstOrFail();
        $payment = $order->payment;

        if (! $payment) {
            return;
        }

        $this->confirmPayment($payment, 'MANUAL-' . strtoupper(uniqid()));
    }
}
