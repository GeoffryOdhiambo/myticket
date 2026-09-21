<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Music', 'icon' => 'musical-note'],
            ['name' => 'Parties', 'icon' => 'sparkles'],
            ['name' => 'Conferences', 'icon' => 'presentation-chart-bar'],
            ['name' => 'Sports', 'icon' => 'trophy'],
            ['name' => 'Festivals', 'icon' => 'sun'],
            ['name' => 'Entertainment', 'icon' => 'film'],
        ];

        foreach ($categories as $category) {
            EventCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                ['name' => $category['name'], 'icon' => $category['icon']]
            );
        }
    }
}
