<?php

namespace Database\Factories;

use App\Models\Ulasan;
use App\Models\User;
use App\Models\Wisata;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ulasan>
 */
class UlasanFactory extends Factory
{
    private static ?array $ulasanData = null;

    /**
     * Load ulasan data from JSON file
     */
    private static function loadUlasanData(): array
    {
        if (self::$ulasanData === null) {
            $path = database_path('seeders/json/ulasan.json');
            $json = file_get_contents($path);
            self::$ulasanData = json_decode($json, true) ?? [];
        }

        return self::$ulasanData;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reviewable_type' => Wisata::class,
            'reviewable_id' => Wisata::factory(),
            'komentar' => fake()->paragraph(2),
            'rating' => fake()->numberBetween(1, 5),
        ];
    }

    /**
     * Create ulasans from JSON data
     */
    public static function createFromJson(): void
    {
        $data = self::loadUlasanData();

        foreach ($data as $item) {
            Ulasan::create([
                'user_id' => $item['user_id'],
                'reviewable_type' => $item['reviewable_type'],
                'reviewable_id' => $item['reviewable_id'],
                'komentar' => $item['komentar'],
                'rating' => $item['rating'] ?? null,
            ]);
        }
    }
}
