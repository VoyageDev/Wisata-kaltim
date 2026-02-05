<?php

namespace Database\Factories;

use App\Models\Wisata;
use App\Models\WisataKuota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WisataKuota>
 */
class WisataKuotaFactory extends Factory
{
    private static ?array $wisataKuotaData = null;

    /**
     * Load wisata kuota data from JSON file
     */
    private static function loadWisataKuotaData(): array
    {
        if (self::$wisataKuotaData === null) {
            $path = database_path('seeders/json/wisata_kuota.json');
            $json = file_get_contents($path);
            self::$wisataKuotaData = json_decode($json, true) ?? [];
        }

        return self::$wisataKuotaData;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kuotaTotal = fake()->numberBetween(20, 100);
        $kuotaTerpakai = fake()->numberBetween(0, $kuotaTotal);

        return [
            'wisata_id' => Wisata::inRandomOrder()->first()?->id ?? Wisata::factory(),
            'tanggal' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'kuota_total' => $kuotaTotal,
            'kuota_terpakai' => $kuotaTerpakai,
        ];
    }

    /**
     * Create wisata kuotas from JSON data
     */
    public static function createFromJson(): void
    {
        $data = self::loadWisataKuotaData();

        foreach ($data as $item) {
            WisataKuota::create([
                'wisata_id' => $item['wisata_id'],
                'tanggal' => $item['tanggal'],
                'kuota_total' => $item['kuota_total'],
                'kuota_terpakai' => $item['kuota_terpakai'],
            ]);
        }
    }
}
