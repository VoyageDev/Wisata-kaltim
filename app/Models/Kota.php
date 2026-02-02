<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kota extends Model
{
    /** @use HasFactory<\Database\Factories\KotaFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
    ];

    public function wisatas(): HasMany
    {
        return $this->hasMany(Wisata::class);
    }

    public function artikels(): HasMany
    {
        return $this->hasMany(Artikel::class);
    }
}
