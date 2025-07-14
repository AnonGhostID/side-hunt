<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'checkout_link',
        'external_id',
        'status',
        'method',
        'amount',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid' || $this->status === 'settled';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isExpired(): bool
    {
        // Check if payment is older than 24 hours and still pending
        return $this->isPending() && $this->created_at->diffInHours(now()) > 24;
    }
}
