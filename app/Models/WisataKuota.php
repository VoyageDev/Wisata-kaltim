<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WisataKuota extends Model
{
    /** @use HasFactory<\Database\Factories\WisataKuotaFactory> */
    use HasFactory;

    protected $fillable = [
        'wisata_id',
        'tanggal',
        'kuota_total',
        'kuota_terpakai',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'kuota_total' => 'integer',
        'kuota_terpakai' => 'integer',
    ];

    public function wisata(): BelongsTo
    {
        return $this->belongsTo(Wisata::class);
    }
}
