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
        // 'alamat',
        // 'jenis_kelamin',
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

    public function kelasDiampuIds($tahunSemesterId = null)
    {
        $query = $this->guruKelas();
        if ($tahunSemesterId) {
            $query->where('tahun_semester_id', $tahunSemesterId);
        }

        return $query->pluck('kelas_id')->unique()->toArray();
    }

    public function kelasDiampu($tahunSemesterId = null)
    {
        $query = $this->guruKelas()->with('kelas');
        if ($tahunSemesterId) {
            $query->where('tahun_semester_id', $tahunSemesterId);
        }

        return $query->get()->pluck('kelas')->unique('id');
    }

    public function isPengajar($tahunSemesterId = null)
    {
        $query = $this->guruKelas()->where('peran', 'pengajar');
        if ($tahunSemesterId) {
            $query->where('tahun_semester_id', $tahunSemesterId);
        }

        return $query->exists();
    }

    public function isWaliKelas($tahunSemesterId = null)
    {
        $query = $this->guruKelas()->where('peran', 'wali');
        if ($tahunSemesterId) {
            $query->where('tahun_semester_id', $tahunSemesterId);
        }

        return $query->exists();
    }
}
