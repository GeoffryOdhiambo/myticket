<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrganizerFactory extends Factory
{
    public function definition(): array
    {
        $business = fake()->company();

        return [
            'name' => fake()->name(),
            'business_name' => $business,
            'email' => Str::slug($business).'@'.fake()->safeEmailDomain(),
            'password' => Hash::make('password'),
            'phone' => '+2547'.fake()->numerify('########'),
            'status' => 'active',
        ];
    }

    public function suspended(): static
    {
        return $this->state(['status' => 'suspended']);
    }
}
