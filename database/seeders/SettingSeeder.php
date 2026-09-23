<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        if (Setting::query()->exists()) {
            return;
        }

        Setting::create([
            'platform_name' => 'MyTicket',
            'support_email' => 'support@myticket.top',
            'support_phone' => '+254 700 000 000',
            'platform_fee_percent' => 5,
            'currency' => 'KES',
            'payment_driver' => 'manual',
        ]);
    }
}
