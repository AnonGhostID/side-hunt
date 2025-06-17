<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Model implements AuthenticatableContract
{
    use HasFactory, Notifiable, Authenticatable;
    // use HasFactory, Notifiable;
    protected $table = 'users'; // pastikan ini eksplisit

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //dewa
        'nama',
        'email',
        'alamat',
        'telpon',
        'password',
        'role',
        'preferensi_user',

        //adam
        'dompet',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pembuat()
    {
        return $this->hasMany(Pekerjaan::class, 'pembuat');
    }

    public function pelamar()
    {
        return $this->belongsToMany(Users::class, 'pelamars');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role == 'mitra';
    }
}
