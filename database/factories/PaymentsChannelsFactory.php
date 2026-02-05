<?php

namespace Database\Factories;

use App\Models\Payments_channels;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payments_channels>
 */
class PaymentsChannelsFactory extends Factory
{
    private static ?array $paymentsChannelData = null;

    /**
     * Load payments channels data from JSON file
     */
    private static function loadPaymentsChannelData(): array
    {
        if (self::$paymentsChannelData === null) {
            $path = database_path('seeders/json/payments_channels.json');
            $json = file_get_contents($path);
            self::$paymentsChannelData = json_decode($json, true) ?? [];
        }

        return self::$paymentsChannelData;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['virtual_account', 'bank_transfer', 'e_wallet']),
            'code' => fake()->unique()->slug(2),
            'name' => fake()->company(),
            'account_number' => fake()->optional()->numerify('##########'),
            'account_name' => fake()->optional()->company(),
            'is_active' => fake()->boolean(80),
        ];
    }

    /**
     * Create payments channels from JSON data
     */
    public static function createFromJson(): void
    {
        $data = self::loadPaymentsChannelData();

        foreach ($data as $item) {
            Payments_channels::create([
                'type' => $item['type'],
                'code' => $item['code'],
                'name' => $item['name'],
                'account_number' => $item['account_number'] ?? null,
                'account_name' => $item['account_name'] ?? null,
                'is_active' => $item['is_active'] ?? true,
            ]);
        }
    }
}
