<?php

namespace Database\Factories;

use App\Models\Kota;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artikel>
 */
class ArtikelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $judul = fake()->sentence(6);
        $hasApiData = fake()->boolean(30);

        $thumbnails = [
            'images/seed/artikels/artikel-1.svg',
            'images/seed/artikels/artikel-2.svg',
            'images/seed/artikels/artikel-3.svg',
        ];

        return [
            'user_id' => User::factory(),
            'kota_id' => Kota::factory(),
            'judul' => rtrim($judul, '.'),
            'slug' => Str::slug($judul),
            'views' => fake()->numberBetween(0, 10000),
            'isi' => fake()->paragraphs(5, true),
            'api_source' => $hasApiData ? fake()->randomElement(['BMKG', 'NewsAPI', 'TravelAPI']) : null,
            'external_id' => $hasApiData ? fake()->uuid() : null,
            'api_data' => $hasApiData ? [
                'source' => fake()->company(),
                'author' => fake()->name(),
                'published' => fake()->dateTime()->format('Y-m-d H:i:s'),
                'category' => fake()->randomElement(['travel', 'culture', 'food', 'nature']),
            ] : null,
            'thumbnail' => fake()->randomElement($thumbnails),
        ];
    }
}
