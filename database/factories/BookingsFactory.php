<?php

namespace Database\Factories;

use App\Models\Bookings;
use App\Models\PaketWisata;
use App\Models\User;
use App\Models\Wisata;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookings>
 */
class BookingsFactory extends Factory
{
    private static ?array $bookingsData = null;

    /**
     * Load bookings data from JSON file
     */
    private static function loadBookingsData(): array
    {
        if (self::$bookingsData === null) {
            $path = database_path('seeders/json/bookings.json');
            $json = file_get_contents($path);
            self::$bookingsData = json_decode($json, true) ?? [];
        }

        return self::$bookingsData;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jumlahOrang = fake()->numberBetween(1, 10);
        $hargaTiket = fake()->numberBetween(100, 2000) * 1000;
        $jumlahTiket = fake()->numberBetween(1, 3);

        return [
            'user_id' => User::factory(),
            'wisata_id' => Wisata::inRandomOrder()->first()?->id ?? Wisata::factory(),
            'paket_wisata_id' => fake()->optional()->randomElement(PaketWisata::pluck('id')->toArray()),
            'tanggal_kunjungan' => fake()->dateTimeBetween('now', '+60 days')->format('Y-m-d'),
            'jumlah_orang' => $jumlahOrang,
            'jumlah_tiket' => $jumlahTiket,
            'kode_tiket' => 'TRV'.date('Ymd').fake()->unique()->numerify('###'),
            'total_harga' => $hargaTiket * $jumlahTiket,
            'status' => fake()->randomElement(['pending', 'paid', 'cancelled', 'done']),
        ];
    }

    /**
     * Create bookings from JSON data
     */
    public static function createFromJson(): void
    {
        $data = self::loadBookingsData();

        foreach ($data as $item) {
            Bookings::create([
                'user_id' => $item['user_id'],
                'wisata_id' => $item['wisata_id'],
                'paket_wisata_id' => $item['paket_wisata_id'] ?? null,
                'tanggal_kunjungan' => $item['tanggal_kunjungan'],
                'jumlah_orang' => $item['jumlah_orang'],
                'jumlah_tiket' => $item['jumlah_tiket'],
                'kode_tiket' => $item['kode_tiket'],
                'total_harga' => $item['total_harga'],
                'status' => $item['status'],
            ]);
        }
    }
}
