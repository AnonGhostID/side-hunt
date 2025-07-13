<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'sender_id',
        'sender_type',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Message belongs to a ticket.
     */
    public function ticket()
    {
        return $this->belongsTo(TiketBantuan::class, 'ticket_id');
    }

    /**
     * Message belongs to a sender (user).
     */
    public function sender()
    {
        return $this->belongsTo(Users::class, 'sender_id');
    }

    /**
     * Check if message is from admin.
     */
    public function isFromAdmin()
    {
        return $this->sender_type === 'admin';
    }

    /**
     * Check if message is from user.
     */
    public function isFromUser()
    {
        return $this->sender_type === 'user';
    }

    /**
     * Mark message as read.
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Check if message is read.
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }
}