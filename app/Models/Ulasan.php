<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Ulasan extends Model
{
    /** @use HasFactory<\Database\Factories\UlasanFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'reviewable_id',
        'reviewable_type',
        'komentar',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Ulasan::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Ulasan::class, 'parent_id')->with(['user', 'replies']);
    }

    public function allReplies()
    {
        return $this->replies()->with('allReplies');
    }
}
