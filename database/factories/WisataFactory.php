<?php

namespace Database\Factories;

use App\Models\Kota;
use App\Models\Wisata;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wisata>
 */
class WisataFactory extends Factory
{
    private static ?array $wisataData = null;

    private static int $currentIndex = 0;

    /**
     * Load wisata data from JSON file
     */
    private static function loadWisataData(): array
    {
        if (self::$wisataData === null) {
            $path = database_path('seeders/json/wisata.json');
            $json = file_get_contents($path);
            self::$wisataData = json_decode($json, true) ?? [];
        }

        return self::$wisataData;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = self::loadWisataData();

        if (! empty($data)) {
            $current = $data[self::$currentIndex % count($data)];
            self::$currentIndex++;

            return [
                'kota_id' => Kota::inRandomOrder()->first()?->id ?? Kota::factory(),
                'name' => $current['name'] ?? fake()->words(3, true),
                'slug' => $current['slug'] ?? Str::slug($current['name']),
                'gambar' => $current['gambar'] ?? null,
                'description' => $current['description'] ?? fake()->paragraphs(3, true),
                'harga_tiket' => $current['harga_tiket'] ?? fake()->numberBetween(5, 200) * 1000,
                'jam_buka' => $current['jam_buka'] ?? fake()->randomElement(['06:00:00', '07:00:00', '08:00:00']),
                'jam_tutup' => $current['jam_tutup'] ?? fake()->randomElement(['17:00:00', '18:00:00', '19:00:00', '20:00:00']),
                'status' => $current['status'] ?? fake()->randomElement(['Open', 'Closed']),
                'alamat' => $current['alamat'] ?? fake()->address(),
            ];
        }

        // Fallback jika JSON kosong
        $name = fake()->words(3, true).' '.fake()->randomElement(['Sungai Mahakam', 'Temple', 'Waterfall', 'Museum', 'Park', 'Hill', 'Lake']);

        $images = [
            'images/seed/wisatas/wisata-1.svg',
            'images/seed/wisatas/wisata-2.svg',
            'images/seed/wisatas/wisata-3.svg',
        ];

        return [
            'kota_id' => Kota::inRandomOrder()->first()?->id ?? Kota::factory(),
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

    /**
     * Create wisatas from JSON data
     */
    public static function createFromJson(): void
    {
        $data = self::loadWisataData();
        $kotas = Kota::all();

        foreach ($data as $item) {
            // Get kota_id dari JSON atau gunakan kota yang tersedia
            $kota_id = $item['kota_id'] ?? ($kotas->isNotEmpty() ? $kotas->first()->id : null);

            if ($kota_id) {
                // Parse harga_tiket - hapus "Rp" dan titik pemisah ribuan, lalu convert ke angka
                $hargaTiket = 0;
                if (isset($item['harga_tiket'])) {
                    // Hapus "Rp", spasi, dan titik, lalu convert ke angka
                    $hargaTiket = preg_replace('/[^0-9]/', '', $item['harga_tiket']);
                    $hargaTiket = (float) $hargaTiket;
                }

                // Parse jam_buka dan jam_tutup - jika bukan format HH:MM:SS, set default
                $jamBuka = '00:00:00'; // Default untuk "24 Jam"
                if (isset($item['jam_buka'])) {
                    if (preg_match('/^(\d{1,2}):(\d{2}):(\d{2})$/', $item['jam_buka'])) {
                        $jamBuka = $item['jam_buka'];
                    } elseif (stripos($item['jam_buka'], '24') !== false) {
                        $jamBuka = '00:00:00'; // 24 jam berarti selalu buka
                    }
                }

                $jamTutup = '23:59:59'; // Default untuk "24 Jam"
                if (isset($item['jam_tutup'])) {
                    if (preg_match('/^(\d{1,2}):(\d{2}):(\d{2})$/', $item['jam_tutup'])) {
                        $jamTutup = $item['jam_tutup'];
                    } elseif (stripos($item['jam_tutup'], '24') !== false) {
                        $jamTutup = '23:59:59'; // 24 jam berarti selalu buka
                    }
                }

                Wisata::create([
                    'kota_id' => $kota_id,
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'gambar' => $item['gambar'] ?? null,
                    'description' => $item['description'] ?? '',
                    'links_maps' => $item['links_maps'] ?? null,
                    'harga_tiket' => $hargaTiket,
                    'jam_buka' => $jamBuka,
                    'jam_tutup' => $jamTutup,
                    'status' => $item['status'] ?? 'Open',
                    'alamat' => $item['alamat'] ?? '',
                ]);
            }
        }
    }
}
