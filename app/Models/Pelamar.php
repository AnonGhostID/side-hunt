<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelamar extends Model
{
    /** @use HasFactory<\Database\Factories\PelamarFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id',
        'status',
        'Tipe_Group',
        'Data_Team'

    ];

    public function user()
    {
        return $this->belongsTo(Users::class);
    }

    public function sidejob()
    {
        return $this->belongsTo(Pekerjaan::class, 'job_id');
    }
}
