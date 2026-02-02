<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Artikel extends Model
{
    /** @use HasFactory<\Database\Factories\ArtikelFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        // 'wisata_id',
        'kota_id',
        'judul',
        'slug',
        'views',
        'isi',
        'api_source',
        'external_id',
        'api_data',
        'thumbnail',
    ];

    protected $casts = [
        'api_data' => 'array',
        'views' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function wisata(): BelongsTo
    // {
    //     return $this->belongsTo(Wisata::class);
    // }
    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class);
    }


    public function ulasans(): MorphMany
    {
        return $this->morphMany(Ulasan::class, 'reviewable');
    }
}
