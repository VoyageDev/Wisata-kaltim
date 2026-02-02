<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Wisata extends Model
{
    /** @use HasFactory<\Database\Factories\WisataFactory> */
    use HasFactory;

    protected $fillable = [
        'kota_id',
        'name',
        'slug',
        'gambar',
        'description',
        'harga_tiket',
        'jam_buka',
        'jam_tutup',
        'status',
        'alamat',
        'links_maps',
        'links_bookings',
    ];

    protected $casts = [
        'jam_buka' => 'datetime:H:i',
        'jam_tutup' => 'datetime:H:i',
        'harga_tiket' => 'integer',
    ];

    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class);
    }

    public function ulasans(): MorphMany
    {
        return $this->morphMany(Ulasan::class, 'reviewable');
    }
}
