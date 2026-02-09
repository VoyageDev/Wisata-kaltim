<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bookings extends Model
{
    /** @use HasFactory<\Database\Factories\BookingsFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wisata_id',
        'paket_wisata_id',
        'tanggal_kunjungan',
        'jumlah_orang',
        'jumlah_tiket',
        'kode_tiket',
        'total_harga',
        'status',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'total_harga' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class);
    }

    public function paketWisata(): BelongsTo
    {
        return $this->belongsTo(PaketWisata::class, 'paket_wisata_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payments::class, 'booking_id');
    }

    /**
     * Get or create WisataKuota for the booking date
     */
    public function getWisataKuota()
    {
        return WisataKuota::where('wisata_id', $this->wisata_id)
            ->where('tanggal', $this->tanggal_kunjungan)
            ->first();
    }

    /**
     * Check if tickets are available for the visit date (considering number of people)
     */
    public function hasAvailableTickets(): bool
    {
        $kuota = $this->getWisataKuota();

        if (! $kuota) {
            return false; // No quota record for this date
        }

        $jumlahOrang = $this->jumlah_orang ?? 1;
        $sisaTiket = $kuota->kuota_total - $kuota->kuota_terpakai;

        return $sisaTiket >= $jumlahOrang;
    }

    /**
     * Get available tickets count for the visit date
     */
    public function getAvailableTicketsCount(): int
    {
        $kuota = $this->getWisataKuota();

        if (! $kuota) {
            return 0;
        }

        return max(0, $kuota->kuota_total - $kuota->kuota_terpakai);
    }

    /**
     * Check if booking can be fulfilled with available tickets
     */
    public function canBeFulfilled(): bool
    {
        return $this->hasAvailableTickets();
    }

    /**
     * Get required tickets for this booking
     */
    public function getRequiredTickets(): int
    {
        return $this->jumlah_orang ?? 1;
    }
}
