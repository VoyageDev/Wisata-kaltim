<?php

namespace Database\Factories;

use App\Models\Kota;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kota>
 */
class KotaFactory extends Factory
{
    private static ?array $kotaData = null;

    private static int $currentIndex = 0;

    /**
     * Load kota data from JSON file
     */
    private static function loadKotaData(): array
    {
        if (self::$kotaData === null) {
            $path = database_path('seeders/json/kota.json');
            $json = file_get_contents($path);
            self::$kotaData = json_decode($json, true) ?? [];
        }

        return self::$kotaData;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = self::loadKotaData();

        if (! empty($data)) {
            $current = $data[self::$currentIndex % count($data)];
            self::$currentIndex++;

            return [
                'name' => $current['name'] ?? fake()->city(),
                'slug' => $current['slug'] ?? Str::slug($current['name']),
                'image' => $current['image'] ?? null,
            ];
        }

        // Fallback jika JSON kosong
        $name = fake()->city();

        $images = [
            'images/seed/kotas/kota-1.svg',
            'images/seed/kotas/kota-2.svg',
            'images/seed/kotas/kota-3.svg',
        ];

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'image' => fake()->boolean(70) ? fake()->randomElement($images) : null,
        ];
    }

    /**
     * Create kotas from JSON data
     */
    public static function createFromJson(): void
    {
        $data = self::loadKotaData();

        foreach ($data as $item) {
            Kota::create([
                'name' => $item['name'],
                'slug' => $item['slug'],
                'image' => $item['image'] ?? null,
            ]);
        }
    }
}
