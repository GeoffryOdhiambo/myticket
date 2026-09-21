<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            EventCategorySeeder::class,
            SuperAdminSeeder::class,
            OrganizerSeeder::class,
            EventSeeder::class,
            TicketTypeSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
