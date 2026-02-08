<?php

namespace Database\Factories;

use App\Models\Artikel;
use App\Models\User;
use App\Models\Wisata;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artikel>
 */
class ArtikelFactory extends Factory
{
    private static ?array $artikelData = null;

    private static int $currentIndex = 0;

    /**
     * Load artikel data from JSON file
     */
    private static function loadArtikelData(): array
    {
        if (self::$artikelData === null) {
            $path = database_path('seeders/json/artikel.json');
            $json = file_get_contents($path);
            self::$artikelData = json_decode($json, true) ?? [];
        }

        return self::$artikelData;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = self::loadArtikelData();

        if (! empty($data)) {
            // Get data from JSON artikel
            $current = $data[self::$currentIndex % count($data)];
            self::$currentIndex++;

            // Keep isi as array for JSON storage
            $isi = is_array($current['isi']) ? $current['isi'] : [$current['isi']];

            return [
                'user_id' => User::factory(),
                'wisata_id' => Wisata::inRandomOrder()->first()?->id ?? Wisata::factory(),
                'judul' => $current['judul'] ?? fake()->sentence(6),
                'slug' => $current['slug'] ?? Str::slug($current['judul']),
                'views' => fake()->numberBetween(0, 10000),
                'isi' => $isi,
                'api_source' => null,
                'external_id' => null,
                'api_data' => null,
                'thumbnail' => 'images/seed/artikel/'.($current['thumbnail'] ?? 'default.jpg'),
            ];
        }

        // Fallback jika JSON kosong
        $judul = fake()->sentence(6);

        $thumbnails = [
            'images/seed/artikels/artikel-1.svg',
            'images/seed/artikels/artikel-2.svg',
            'images/seed/artikels/artikel-3.svg',
        ];

        return [
            'user_id' => User::factory(),
            'wisata_id' => Wisata::inRandomOrder()->first()?->id ?? Wisata::factory(),
            'judul' => rtrim($judul, '.'),
            'slug' => Str::slug($judul),
            'views' => fake()->numberBetween(0, 10000),
            'isi' => [
                fake()->paragraph(),
                fake()->paragraph(),
                fake()->paragraph(),
            ],
            'api_source' => null,
            'external_id' => null,
            'api_data' => null,
            'thumbnail' => fake()->randomElement($thumbnails),
        ];
    }

    /**
     * Create artikels from JSON data
     */
    public static function createFromJson(?User $user = null): void
    {
        $data = self::loadArtikelData();
        $wisatas = Wisata::all()->keyBy('id');

        if ($wisatas->isEmpty()) {
            return;
        }

        $artikels = [];
        foreach ($data as $item) {
            // Keep isi as array for JSON storage
            $isi = is_array($item['isi']) ? $item['isi'] : [$item['isi']];
            $wisata_id = isset($item['wisata_id']) && $wisatas->has($item['wisata_id']) ? $item['wisata_id'] : $wisatas->keys()->random();

            $artikels[] = [
                'user_id' => $user?->id ?? 1,
                'wisata_id' => $wisata_id,
                'judul' => $item['judul'] ?? 'Artikel',
                'slug' => $item['slug'] ?? Str::slug($item['judul'] ?? 'artikel'),
                'views' => random_int(0, 10000),
                'isi' => json_encode($isi),
                'api_source' => null,
                'external_id' => null,
                'api_data' => null,
                'thumbnail' => 'images/seed/artikel/'.($item['thumbnail'] ?? 'default.jpg'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Batch insert untuk performa
        if (! empty($artikels)) {
            Artikel::insert($artikels);
        }
    }
}
