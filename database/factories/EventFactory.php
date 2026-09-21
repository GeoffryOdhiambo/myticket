<?php

namespace Database\Factories;

use App\Models\EventCategory;
use App\Models\Organizer;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public const IMAGES = [
        'music' => [
            'photo-1470229722913-7c0e2dbbafd3',
            'photo-1540039155733-5bb30b53aa14',
            'photo-1524368535928-5b5e00ddc76b',
        ],
        'parties' => [
            'photo-1470225620780-dba8ba36b745',
            'photo-1543007630-9710e4a00a20',
        ],
        'conferences' => [
            'photo-1511578314322-379afb476865',
            'photo-1508997449629-303059a039c0',
        ],
        'sports' => [
            'photo-1461896836934-ffe607ba8211',
            'photo-1517649763962-0c623066013b',
        ],
        'festivals' => [
            'photo-1501281668745-f7f57925c3b4',
            'photo-1533174072545-7a4b6ad7a6c3',
        ],
        'entertainment' => [
            'photo-1478147427282-58a87a120781',
            'photo-1543007630-9710e4a00a20',
        ],
    ];

    public const NAMES = [
        'music' => ['Nairobi Live Sessions', 'Amapiano Night Nairobi', 'Sundowner Sessions', 'Coast Beats Concert'],
        'parties' => ['Diani Beach Party', 'Rooftop Vibes Nairobi', 'Mombasa White Party', 'Nairobi Nights Out'],
        'conferences' => ['Nairobi Tech Summit', 'East Africa Business Forum', 'Kisumu Innovation Conference', 'Startup Founders Summit'],
        'sports' => ['Nairobi City Marathon', 'Rift Valley Cycling Classic', 'Coastal Beach Volleyball Cup', 'Nairobi Sevens Fan Day'],
        'festivals' => ['Nyama Choma Festival', 'Nairobi Food & Culture Festival', 'Lake Naivasha Music Festival', 'Nairobi Arts Festival'],
        'entertainment' => ['Nairobi Comedy Night', 'Stand-Up Kenya Live', 'Nairobi Film & Culture Night', 'Improv Comedy Showcase'],
    ];

    public const VENUES = [
        ['venue' => 'KICC Grounds', 'location' => 'Nairobi'],
        ['venue' => 'Carnivore Grounds', 'location' => 'Nairobi'],
        ['venue' => 'Uhuru Gardens', 'location' => 'Nairobi'],
        ['venue' => 'Bomas of Kenya', 'location' => 'Nairobi'],
        ['venue' => 'Nyayo National Stadium', 'location' => 'Nairobi'],
        ['venue' => 'Sarit Expo Centre', 'location' => 'Nairobi'],
        ['venue' => 'Diani Beach Grounds', 'location' => 'Diani, Mombasa'],
        ['venue' => 'Mombasa Go-Down', 'location' => 'Mombasa'],
        ['venue' => 'Kisumu Social Centre', 'location' => 'Kisumu'],
        ['venue' => 'Lake Naivasha Resort Grounds', 'location' => 'Naivasha'],
    ];

    public function definition(): array
    {
        $categorySlug = fake()->randomElement(array_keys(self::NAMES));
        $venue = fake()->randomElement(self::VENUES);
        $name = fake()->unique()->randomElement(self::NAMES[$categorySlug]);
        $image = fake()->randomElement(self::IMAGES[$categorySlug]);
        $start = fake()->numberBetween(9, 18);

        return [
            'organizer_id' => Organizer::factory(),
            'category_id' => EventCategory::where('slug', $categorySlug)->value('id') ?? EventCategory::factory(),
            'name' => $name,
            'description' => fake()->paragraphs(3, true),
            'image_url' => "https://images.unsplash.com/{$image}?auto=format&fit=crop&w=1200&q=80",
            'venue' => $venue['venue'],
            'location' => $venue['location'],
            'event_date' => fake()->dateTimeBetween('+3 days', '+3 months')->format('Y-m-d'),
            'start_time' => sprintf('%02d:00:00', $start),
            'end_time' => sprintf('%02d:00:00', min($start + fake()->numberBetween(2, 5), 23)),
            'contact_email' => fake()->companyEmail(),
            'contact_phone' => '+2547' . fake()->numerify('########'),
            'status' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function unpublished(): static
    {
        return $this->state(['status' => 'unpublished']);
    }
}
