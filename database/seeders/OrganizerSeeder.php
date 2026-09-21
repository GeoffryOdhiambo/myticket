<?php

namespace Database\Seeders;

use App\Models\Organizer;
use Illuminate\Database\Seeder;

class OrganizerSeeder extends Seeder
{
    public function run(): void
    {
        $organizers = [
            ['name' => 'Wanjiru Kamau', 'business_name' => 'Nairobi Live Events', 'email' => 'organizer1@tiko.africa', 'phone' => '+254712345601'],
            ['name' => 'Brian Otieno', 'business_name' => 'Coastline Experiences', 'email' => 'organizer2@tiko.africa', 'phone' => '+254712345602'],
            ['name' => 'Amina Hassan', 'business_name' => 'Rift Valley Productions', 'email' => 'organizer3@tiko.africa', 'phone' => '+254712345603'],
        ];

        foreach ($organizers as $organizer) {
            Organizer::updateOrCreate(
                ['email' => $organizer['email']],
                [
                    'name' => $organizer['name'],
                    'business_name' => $organizer['business_name'],
                    'phone' => $organizer['phone'],
                    'password' => 'password',
                    'status' => 'active',
                ]
            );
        }
    }
}
