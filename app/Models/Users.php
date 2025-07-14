<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Namu\WireChat\Traits\Chatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Users extends Model implements AuthenticatableContract
{
    use HasFactory, Notifiable, Authenticatable, Chatable;
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
        'email_verified_at',

        //adam
        'dompet',
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
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function getDisplayNameAttribute(): ?string
    {
      return $this->nama ?? 'user';
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

    public function searchChatables(string $query)
    {
     $searchableFields = ['nama'];
     return Users::where(function ($queryBuilder) use ($searchableFields, $query) {
        foreach ($searchableFields as $field) {
                $queryBuilder->orWhere($field, 'LIKE', '%'.$query.'%');
        }
      })
        ->limit(20)
        ->get();
    }

    public function getCoverUrlAttribute(): ?string
    {
      return $this->avatar_url ?? null;
    }

    public function getProfileUrlAttribute(): ?string
    {
        //belum ada
      return route('profile', ['id' => $this->id]);
    }

    public function canCreateGroups(): bool
    {
      return $this->hasVerifiedEmail();
    }

    public function canCreateChats(): bool
    {
     return $this->hasVerifiedEmail();
    }
}
