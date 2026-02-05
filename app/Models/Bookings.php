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
}
