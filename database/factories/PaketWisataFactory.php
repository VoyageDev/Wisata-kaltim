<?php

namespace Database\Factories;

use App\Models\PaketWisata;
use App\Models\Wisata;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaketWisata>
 */
class PaketWisataFactory extends Factory
{
    private static ?array $paketWisataData = null;

    /**
     * Load paket wisata data from JSON file
     */
    private static function loadPaketWisataData(): array
    {
        if (self::$paketWisataData === null) {
            $path = database_path('seeders/json/paket_wisata.json');
            $json = file_get_contents($path);
            self::$paketWisataData = json_decode($json, true) ?? [];
        }

        return self::$paketWisataData;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'wisata_id' => Wisata::inRandomOrder()->first()?->id ?? Wisata::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'gambar' => 'paket-'.fake()->numberBetween(1, 10).'.jpg',
            'jumlah_orang' => fake()->randomElement([2, 4, 5, 8, 10]),
            'harga_paket' => fake()->numberBetween(200, 5000) * 1000,
        ];
    }

    /**
     * Create paket wisatas from JSON data
     */
    public static function createFromJson(): void
    {
        $data = self::loadPaketWisataData();

        foreach ($data as $item) {
            PaketWisata::create([
                'wisata_id' => $item['wisata_id'],
                'name' => $item['name'],
                'slug' => $item['slug'],
                'gambar' => $item['gambar'],
                'jumlah_orang' => $item['jumlah_orang'],
                'harga_paket' => $item['harga_paket'],
            ]);
        }
    }
}
