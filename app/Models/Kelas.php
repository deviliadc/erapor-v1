<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama',
    ];

    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'kelas_siswa');
    }

    public function kelasSiswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }

    public function lingkupMateri()
    {
        return $this->hasMany(LingkupMateri::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function guruKelas()
    {
        return $this->hasMany(GuruKelas::class, 'kelas_id');
    }

    public function mapel()
    {
        return $this->belongsToMany(Mapel::class, 'guru_kelas', 'kelas_id', 'mapel_id')
            ->where('peran', 'pengajar');
    }

    public function mapelAktif()
    {
        $tahunAktif = TahunSemester::where('is_active', true)->first();
        return $this->belongsToMany(Mapel::class, 'guru_kelas', 'kelas_id', 'mapel_id')
            ->where('peran', 'pengajar')
            ->where('tahun_semester_id', $tahunAktif?->id);
    }
}
