<?php

namespace App\Providers;

use App\Services\Notifications\LogWhatsAppService;
use App\Services\Notifications\WhatsAppServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(WhatsAppServiceInterface::class, match (config('services.whatsapp.driver', 'log')) {
            default => LogWhatsAppService::class,
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
