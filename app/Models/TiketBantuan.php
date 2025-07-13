<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiketBantuan extends Model
{
    use HasFactory;

    protected $table = 'TiketBantuan';

    protected $fillable = [
        'user_id',
        'type',
        'subject',
        'description',
        'status',
        'pihak_terlapor',
        'tanggal_kejadian',
        'bukti_pendukung',
    ];

    protected $casts = [
        'bukti_pendukung' => 'array',
        'tanggal_kejadian' => 'date',
    ];

    /**
     * Ticket belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\Users::class, 'user_id');
    }

    /**
     * Ticket has many messages (conversation).
     */
    public function messages()
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    /**
     * Get the latest message in the conversation.
     */
    public function latestMessage()
    {
        return $this->hasOne(TicketMessage::class, 'ticket_id')->latest();
    }

    /**
     * Get unread messages count for a specific user type.
     */
    public function unreadMessagesCount($senderType = null)
    {
        $query = $this->messages()->whereNull('read_at');
        
        if ($senderType) {
            $query->where('sender_type', '!=', $senderType);
        }
        
        return $query->count();
    }

    /**
     * Check if ticket has any unread messages for admin.
     */
    public function hasUnreadForAdmin()
    {
        return $this->messages()->where('sender_type', 'user')->whereNull('read_at')->exists();
    }

    /**
     * Check if ticket has any unread messages for user.
     */
    public function hasUnreadForUser()
    {
        return $this->messages()->where('sender_type', 'admin')->whereNull('read_at')->exists();
    }
}
