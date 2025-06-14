<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'alamat',
        'telpon',
        'isAdmin',
        'password',
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

    public function pembuat(){
        return $this->hasMany(SideJob::class,'pembuat');
    }
    
    public function pelamar(){
        return $this->belongsToMany(User::class, 'pelamars');
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin == true;
    }
}
