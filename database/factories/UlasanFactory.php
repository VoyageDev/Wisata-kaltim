<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wisata;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ulasan>
 */
class UlasanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reviewable_type' => Wisata::class,
            'reviewable_id' => Wisata::factory(),
            'komentar' => fake()->paragraph(2),
            'rating' => fake()->numberBetween(1, 5),
        ];
    }
}
