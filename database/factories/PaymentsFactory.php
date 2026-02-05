<?php

namespace Database\Factories;

use App\Models\Bookings;
use App\Models\Payments;
use App\Models\Payments_channels;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payments>
 */
class PaymentsFactory extends Factory
{
    private static ?array $paymentsData = null;

    /**
     * Load payments data from JSON file
     */
    private static function loadPaymentsData(): array
    {
        if (self::$paymentsData === null) {
            $path = database_path('seeders/json/payments.json');
            $json = file_get_contents($path);
            self::$paymentsData = json_decode($json, true) ?? [];
        }

        return self::$paymentsData;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'paid', 'failed']);

        return [
            'booking_id' => Bookings::factory(),
            'payment_channel_id' => Payments_channels::inRandomOrder()->first()?->id,
            'metode' => fake()->randomElement(['BCA Virtual Account', 'Transfer BCA', 'GoPay', 'OVO']),
            'jumlah' => fake()->numberBetween(100, 5000) * 1000,
            'status' => $status,
            'paid_at' => $status === 'paid' ? fake()->dateTimeBetween('-30 days', 'now') : null,
        ];
    }

    /**
     * Create payments from JSON data
     */
    public static function createFromJson(): void
    {
        $data = self::loadPaymentsData();

        foreach ($data as $item) {
            Payments::create([
                'booking_id' => $item['booking_id'],
                'payment_channel_id' => $item['payment_channel_id'] ?? null,
                'metode' => $item['metode'],
                'jumlah' => $item['jumlah'],
                'status' => $item['status'],
                'paid_at' => $item['paid_at'] ?? null,
            ]);
        }
    }
}
