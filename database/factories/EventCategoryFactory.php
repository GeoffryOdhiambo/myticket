<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Music', 'Parties', 'Conferences', 'Sports', 'Festivals', 'Entertainment',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon' => 'musical-note',
        ];
    }
}
