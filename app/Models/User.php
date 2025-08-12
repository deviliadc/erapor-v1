<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\CustomResetPassword;
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
        // 'name',
        'username',
        'email',
        'profile_photo',
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

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Roles
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    // Relasi ke Guru (profil)
    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    // Relasi ke kelas sebagai guru di tabel pivot guru_kelas
    public function kelasDibimbing()
    {
        return $this->belongsToMany(Kelas::class, 'guru_kelas', 'guru_id', 'kelas_id')
            ->withPivot('tahun_ajaran_id')
            ->withTimestamps();
    }

    public function getFilamentName(): string
    {
        return $this->username ?? 'User';
    }

    public function getRoleAttribute()
    {
        return $this->roles()->orderBy('name')->first()?->name ?? null;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
}
