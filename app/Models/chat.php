<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chat extends Model
{
    /** @use HasFactory<\Database\Factories\ChatFactory> */
    use HasFactory;

    protected $fillable = [
        'sender',
        'receiver',
        'contents',
        'file_json',
        'extension',
        'nama_file',
        'chat_references',
        'body_chat_references',
        'pekerjaan_id',
        'Lamaran_status',
        'status'
    ];
}