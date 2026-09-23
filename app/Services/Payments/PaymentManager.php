<?php

namespace App\Services\Payments;

use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use RuntimeException;

class PaymentManager
{
    public function __construct(protected Application $app) {}

    /**
     * Resolve a payment driver. Defaults to the platform's configured
     * driver (Settings > Payment Driver) when none is given explicitly.
     *
     * The manual driver self-confirms payment with no money changing
     * hands — it exists purely so checkout is testable without live
     * credentials, and must never be reachable in production regardless
     * of what Settings says or who asks for it by name.
     */
    public function driver(?string $name = null): PaymentServiceInterface
    {
        $name ??= Setting::current()->payment_driver;

        if ($name !== 'mpesa' && $this->app->environment('production')) {
            throw new RuntimeException('The manual payment driver is disabled in production.');
        }

        return match ($name) {
            'mpesa' => $this->app->make(MpesaPaymentService::class),
            default => $this->app->make(ManualPaymentService::class),
        };
    }
}
