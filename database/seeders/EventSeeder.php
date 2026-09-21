<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Organizer;
use Database\Factories\EventFactory;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $organizers = Organizer::all();
        $dayOffset = 5;

        foreach (EventFactory::NAMES as $categorySlug => $names) {
            $category = EventCategory::where('slug', $categorySlug)->first();

            foreach (array_slice($names, 0, 2) as $index => $name) {
                if (Event::where('name', $name)->exists()) {
                    continue;
                }

                $venue = EventFactory::VENUES[array_rand(EventFactory::VENUES)];
                $image = EventFactory::IMAGES[$categorySlug][$index % count(EventFactory::IMAGES[$categorySlug])];
                $start = fake()->randomElement([9, 11, 14, 17, 19]);

                Event::create([
                    'organizer_id' => $organizers->random()->id,
                    'category_id' => $category->id,
                    'name' => $name,
                    'description' => $this->description($name, $categorySlug),
                    'image_url' => "https://images.unsplash.com/{$image}?auto=format&fit=crop&w=1200&q=80",
                    'venue' => $venue['venue'],
                    'location' => $venue['location'],
                    'event_date' => now()->addDays($dayOffset)->toDateString(),
                    'start_time' => sprintf('%02d:00:00', $start),
                    'end_time' => sprintf('%02d:00:00', min($start + 4, 23)),
                    'contact_email' => 'events@tiko.africa',
                    'contact_phone' => '+254712345600',
                    'status' => 'published',
                ]);

                $dayOffset += 6;
            }
        }
    }

    private function description(string $name, string $category): string
    {
        $intros = [
            'music' => "Get ready for a night of unforgettable live music at {$name}. Expect top performers, great sound, and an electric crowd.",
            'parties' => "{$name} brings the best party atmosphere in town — great music, good vibes, and an unforgettable night out.",
            'conferences' => "{$name} brings together industry leaders, innovators, and professionals for a day of insight, networking, and growth.",
            'sports' => "Join thousands of fans at {$name} for a day of competitive action, community spirit, and non-stop excitement.",
            'festivals' => "{$name} celebrates culture, food, music, and community in one unforgettable experience for the whole family.",
            'entertainment' => "{$name} promises a night of laughter, live performances, and entertainment you won't want to miss.",
        ];

        return ($intros[$category] ?? "{$name} is one of Tiko's most anticipated events.")
            .' Doors open early, so grab your ticket in advance and secure your spot.';
    }
}
