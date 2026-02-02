<?php

namespace Database\Seeders;

use App\Models\Artikel;
use App\Models\Kota;
use App\Models\Ulasan;
use App\Models\User;
use App\Models\Wisata;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create regular users
        User::factory()->admin()->create();
        $users = User::factory(10)->create();

        // Create kotas
        $kotas = Kota::factory(5)->create();

        // Create wisatas (linked to kotas)
        $wisatas = Wisata::factory(15)->create([
            'kota_id' => $kotas->random()->id,
        ]);

        // buat komentar user untuk wisata dan artikel
        foreach ($users as $user) {
            // wisata
            Ulasan::factory(rand(1, 3))->create([
                'user_id' => $user->id,
                'reviewable_type' => Wisata::class,
                'reviewable_id' => $wisatas->random()->id,
            ]);

            // artikel
            Ulasan::factory(rand(0, 2))->create([
                'user_id' => $user->id,
                'reviewable_type' => Artikel::class,
                'reviewable_id' => Artikel::inRandomOrder()->first()?->id ?? 1,
            ]);
        }

        // Create artikels
        Artikel::factory(20)->create([
            'user_id' => $users->random()->id,
            'kota_id' => $kotas->random()->id,
        ]);
    }
}
