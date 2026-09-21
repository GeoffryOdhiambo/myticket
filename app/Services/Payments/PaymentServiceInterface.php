<?php

namespace App\Services\Payments;

use App\Models\Order;

interface PaymentServiceInterface
{
    /**
     * Start payment for the given order (e.g. trigger an STK push, or
     * record a manual-payment placeholder awaiting confirmation).
     */
    public function initiate(Order $order): void;

    /**
     * Handle an asynchronous payment provider callback/confirmation payload.
     */
    public function handleCallback(array $payload): void;
}
