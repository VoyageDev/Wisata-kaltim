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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('wisata_id')->constrained('wisatas')->onDelete('cascade');
            $table->foreignId('paket_wisata_id')
                ->nullable()
                ->constrained('paket_wisatas')
                ->onDelete('set null');
            $table->date('tanggal_kunjungan');
            $table->integer('jumlah_orang');
            $table->integer('jumlah_tiket')->default(1);
            $table->string('kode_tiket')->unique();
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', ['pending', 'paid', 'cancelled', 'done'])
                ->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
