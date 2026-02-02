<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kota>
 */
class KotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
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
}
