<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'email',
        'no_hp',
        'alamat',
        'jenis_kelamin',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class); // sebagai wali kelas
    }

    public function guruKelas()
    {
        return $this->hasMany(GuruKelas::class);
    }

    public function isPengajar()
    {
        return $this->guruKelas()->where('peran', 'pengajar')->exists();
    }

    public function isWaliKelas()
    {
        return $this->guruKelas()->where('peran', 'wali')->exists();
    }
}
