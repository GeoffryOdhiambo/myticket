<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tiko.africa'],
            ['name' => 'Tiko Super Admin', 'password' => 'password']
        );
    }
}
