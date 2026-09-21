<?php

namespace App\Services\Payments;

use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;

class PaymentManager
{
    public function __construct(protected Application $app)
    {
    }

    /**
     * Resolve a payment driver. Defaults to the platform's configured
     * driver (Settings > Payment Driver) when none is given explicitly.
     */
    public function driver(?string $name = null): PaymentServiceInterface
    {
        $name ??= Setting::current()->payment_driver;

        return match ($name) {
            'mpesa' => $this->app->make(MpesaPaymentService::class),
            default => $this->app->make(ManualPaymentService::class),
        };
    }
}
