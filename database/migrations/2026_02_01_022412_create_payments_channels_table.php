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
        Schema::create('payments_channels', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['virtual_account', 'bank_transfer', 'e_wallet']);
            $table->string('code')->unique()->comment('bca_va, qris, bni_manual');
            $table->string('name'); // contoh bank Bca, gopay
            $table->string('account_number')->nullable()->comment('No Rekening Admin / Tujuan Transfer');
            $table->string('account_name')->nullable()->comment('Atas Nama Rekening Admin');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments_channels');
    }
};
