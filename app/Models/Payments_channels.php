<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payments_channels extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentsChannelsFactory> */
    use HasFactory;

    protected $table = 'payments_channels';

    protected $fillable = [
        'type',
        'code',
        'name',
        'account_number',
        'account_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payments::class, 'payment_channel_id');
    }
}
