<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'subject',
        'message',
        'response',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'admin_id');
    }
}
