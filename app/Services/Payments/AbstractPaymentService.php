<?php

namespace App\Services\Payments;

use App\Models\Payment;
use App\Services\TicketService;
use Illuminate\Support\Facades\DB;

abstract class AbstractPaymentService implements PaymentServiceInterface
{
    public function __construct(protected TicketService $tickets)
    {
    }

    /**
     * Mark a payment (and its order) as paid and generate tickets.
     * Safe against duplicate callbacks: a payment that is already paid
     * short-circuits without generating tickets twice.
     */
    protected function confirmPayment(Payment $payment, ?string $providerReference = null, ?array $rawResponse = null): void
    {
        DB::transaction(function () use ($payment, $providerReference, $rawResponse) {
            $payment = Payment::whereKey($payment->id)->lockForUpdate()->first();

            if ($payment->isPaid()) {
                return;
            }

            $payment->update([
                'status' => 'paid',
                'provider_reference' => $providerReference,
                'raw_response' => $rawResponse,
            ]);

            $payment->order->markAsPaid();
        });

        $this->tickets->generateTicketsForOrder($payment->order()->first());
    }

    protected function failPayment(Payment $payment, ?array $rawResponse = null): void
    {
        $payment->update(['status' => 'failed', 'raw_response' => $rawResponse]);
        $payment->order->update(['status' => 'failed']);
    }
}
