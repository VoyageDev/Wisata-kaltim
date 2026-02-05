<?php

namespace Database\Seeders;

use App\Models\Kota;
use App\Models\User;
use App\Models\Wisata;
use Database\Factories\ArtikelFactory;
use Database\Factories\BookingsFactory;
use Database\Factories\KotaFactory;
use Database\Factories\PaketWisataFactory;
use Database\Factories\PaymentsChannelsFactory;
use Database\Factories\PaymentsFactory;
use Database\Factories\UlasanFactory;
use Database\Factories\WisataFactory;
use Database\Factories\WisataKuotaFactory;
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
        $users = User::factory(4)->create(); // Reduced to 4 users since we have specific user_ids in JSON

        // Create kotas from JSON
        KotaFactory::createFromJson();
        $kotas = Kota::all();

        // Create wisatas from JSON
        WisataFactory::createFromJson();
        $wisatas = Wisata::all();

        // Create artikels from JSON
        ArtikelFactory::createFromJson(user: $users->random());

        // Create payment channels from JSON
        PaymentsChannelsFactory::createFromJson();

        // Create paket wisatas from JSON
        PaketWisataFactory::createFromJson();

        // Create wisata kuotas from JSON
        WisataKuotaFactory::createFromJson();

        // Create ulasans from JSON
        UlasanFactory::createFromJson();

        // Create bookings from JSON
        BookingsFactory::createFromJson();

        // Create payments from JSON
        PaymentsFactory::createFromJson();
    }
}
