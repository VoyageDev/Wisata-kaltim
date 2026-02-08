<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaketWisata extends Model
{
    /** @use HasFactory<\Database\Factories\PaketWisataFactory> */
    use HasFactory;

    protected $fillable = [
        'wisata_id',
        'name',
        'slug',
        'gambar',
        'jumlah_orang',
        'harga_paket',
    ];

    protected $casts = [
        'harga_paket' => 'decimal:2',
    ];

    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class);
    }
}
