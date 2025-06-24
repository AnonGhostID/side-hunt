<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'response',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }
}
