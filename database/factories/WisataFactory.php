<?php

namespace Database\Factories;

use App\Models\Kota;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wisata>
 */
class WisataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true).' '.fake()->randomElement(['Sungai Mahakam', 'Temple', 'Waterfall', 'Museum', 'Park', 'Hill', 'Lake']);

        $images = [
            'images/seed/wisatas/wisata-1.svg',
            'images/seed/wisatas/wisata-2.svg',
            'images/seed/wisatas/wisata-3.svg',
        ];

        return [
            'kota_id' => Kota::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'gambar' => fake()->randomElement($images),
            'description' => fake()->paragraphs(3, true),
            'harga_tiket' => fake()->numberBetween(5, 200) * 1000,
            'jam_buka' => fake()->randomElement(['06:00:00', '07:00:00', '08:00:00']),
            'jam_tutup' => fake()->randomElement(['17:00:00', '18:00:00', '19:00:00', '20:00:00']),
            'status' => fake()->randomElement(['Open', 'Closed']),
            'alamat' => fake()->address(),
        ];
    }
}
