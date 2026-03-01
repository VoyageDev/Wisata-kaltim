<?php

namespace App\Console\Commands;

use App\Models\Bookings;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MarkDoneBookings extends Command
{
    // Nama command untuk dijalankan
    protected $signature = 'booking:mark-done';

    // Deskripsi command
    protected $description = 'Mengubah status booking dari paid menjadi done 1 hari setelah tanggal kunjungan';

    public function handle()
    {
        // Cari booking dengan status 'paid' di mana tanggal kunjungannya adalah kemarin (atau sebelumnya)
        $completedBookings = Bookings::where('status', 'paid')
            ->whereDate('tanggal_kunjungan', '<', Carbon::today())
            ->get();

        $count = 0;

        foreach ($completedBookings as $booking) {
            $booking->update(['status' => 'done']);
            $count++;
        }

        // Tampilkan pesan di terminal (untuk keperluan testing/log)
        $this->info("Berhasil mengubah {$count} booking menjadi done.");
    }
}
