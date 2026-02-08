<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artikels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('wisata_id')->constrained('wisatas')->onDelete('cascade');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->bigInteger('views')->default(0);
            $table->json('isi');

            // kalau mau API
            $table->string('api_source')->nullable(); // Nama sumber API (misal: 'BMKG' atau 'NewsAPI')
            $table->string('external_id')->nullable();
            $table->json('api_data')->nullable();

            $table->string('thumbnail');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};
