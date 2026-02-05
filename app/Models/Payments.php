<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payments extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentsFactory> */
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_channel_id',
        'metode',
        'jumlah',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Bookings::class);
    }

    public function paymentChannel(): BelongsTo
    {
        return $this->belongsTo(Payments_channels::class, 'payment_channel_id');
    }
}
