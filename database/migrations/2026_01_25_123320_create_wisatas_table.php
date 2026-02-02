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
        Schema::create('wisatas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kota_id')->constrained('kotas')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('gambar')->nullable();
            $table->text('description');
            $table->string('links_maps')->nullable();
            $table->string('harga_tiket');
            $table->string('links_bookings');
            $table->time('jam_buka');
            $table->time('jam_tutup');
            $table->enum('status', ['Open', 'Closed'])->default('Open');
            $table->string('alamat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisatas');
    }
};
