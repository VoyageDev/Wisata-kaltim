<?php

namespace Database\Seeders;

use Database\Factories\ArtikelFactory;
use Illuminate\Database\Seeder;

class ArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed dari JSON data
        ArtikelFactory::createFromJson();
    }
}
