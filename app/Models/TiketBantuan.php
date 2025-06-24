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
        'subject',
        'description',
        'status',
        'admin_response',
    ];

    /**
     * Ticket belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\Users::class, 'user_id');
    }
}
