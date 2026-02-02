<?php

namespace Database\Seeders;

use App\Models\Artikel;
use App\Models\Kota;
use App\Models\Ulasan;
use App\Models\User;
use App\Models\Wisata;
use Database\Factories\ArtikelFactory;
use Database\Factories\KotaFactory;
use Database\Factories\WisataFactory;
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

        // Create kotas from JSON
        KotaFactory::createFromJson();
        $kotas = Kota::all();

        // Create wisatas from JSON
        WisataFactory::createFromJson();
        $wisatas = Wisata::all();

        // Create artikels from JSON
        ArtikelFactory::createFromJson(user: $users->random());

        // buat komentar user untuk wisata dan artikel
        foreach ($users as $user) {
            // wisata
            if ($wisatas->isNotEmpty()) {
                Ulasan::factory(rand(1, 3))->create([
                    'user_id' => $user->id,
                    'reviewable_type' => Wisata::class,
                    'reviewable_id' => $wisatas->random()->id,
                ]);
            }

            // artikel
            $artikel = Artikel::inRandomOrder()->first();
            if ($artikel) {
                Ulasan::factory(rand(0, 2))->create([
                    'user_id' => $user->id,
                    'reviewable_type' => Artikel::class,
                    'reviewable_id' => $artikel->id,
                ]);
            }
        }
    }
}
