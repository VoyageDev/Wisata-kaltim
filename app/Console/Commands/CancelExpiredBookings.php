<?php

namespace App\Console\Commands;

use App\Models\Bookings;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CancelExpiredBookings extends Command
{
    // Nama command yang akan dijalankan di terminal
    protected $signature = 'booking:cancel-expired';

    // Deskripsi command
    protected $description = 'Membatalkan booking pending yang sudah melewati batas waktu 1 hari';

    public function handle()
    {
        // Cari booking yang statusnya pending dan dibuat lebih dari 24 jam yang lalu
        $expiredBookings = Bookings::where('status', 'pending')
            ->where('created_at', '<=', Carbon::now()->subDay())
            ->get();

        $count = 0;

        foreach ($expiredBookings as $booking) {
            // Ubah status jadi cancelled
            $booking->update(['status' => 'cancelled']);

            // Jika kamu memotong kuota saat status pending,
            // kamu bisa mengembalikan kuotanya di sini.

            $count++;
        }

        // Tampilkan pesan di terminal
        $this->info("Berhasil membatalkan {$count} booking yang kedaluwarsa.");
    }
}
