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
    /**
     * Define the model's default state.
     * Catatan: Dengan logic baru, kuota default ada di wisata.kuota_default
     * WisataKuota hanya untuk override kuota atau tutup tanggal tertentu
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wisata_id' => Wisata::inRandomOrder()->first()?->id ?? Wisata::factory(),
            'tanggal' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'kuota_total' => fake()->numberBetween(20, 100), // override kuota
            'kuota_terpakai' => 0,
            'status' => true, // 1 = buka, 0 = tutup
        ];
    }

    /**
     * State untuk tanggal tutup (status = 0)
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * State untuk menggunakan kuota default (kuota_total = null)
     */
    public function useDefault(): static
    {
        return $this->state(fn (array $attributes) => [
            'kuota_total' => null,
        ]);
    }
}
