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
        'fase_id',
    ];

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }

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

    // public function waliKelas()
    // {
    //     return $this->belongsTo(Guru::class, 'guru_id');
    // }

    public function guruKelas()
    {
        return $this->hasMany(GuruKelas::class, 'kelas_id');
    }

    // Mendapatkan wali kelas (melalui guru_kelas dengan peran = 'wali')
    // public function waliKelas($tahunAjaranId = null)
    // {
    //     $query = $this->guruKelas()->where('peran', 'wali');
    //     if ($tahunAjaranId) {
    //         $query->where('tahun_ajaran_id', $tahunAjaranId);
    //     } else {
    //         // Default: ambil dari tahun semester aktif
    //         $tahunAktif = TahunAjaran::where('is_active', true)->first();
    //         if ($tahunAktif) {
    //             $query->where('tahun_ajaran_id', $tahunAktif->id);
    //         }
    //     }

    //     return $query->with('guru')->first()?->guru; // ambil relasi guru dari guru_kelas
    // }

    public function guruMapel()
    {
        return $this->hasMany(GuruKelas::class, 'kelas_id')->where('peran', 'pengajar');
    }

    // Mendapatkan daftar pengajar mapel (melalui guru_kelas)
    // public function getMapel($tahunAjaranId = null)
    // {
    //     $query = GuruKelas::where('kelas_id', $this->id)
    //         ->where('peran', 'pengajar');

    //     if ($tahunAjaranId) {
    //         $query->where('tahun_ajaran_id', $tahunAjaranId);
    //     }

    //     return $query->with('mapel')->get()->pluck('mapel');
    // }

    public function getMapelByTahunAjaran($tahunAjaranId = null)
    {
        $query = GuruKelas::where('kelas_id', $this->id)
            ->where('peran', 'pengajar');

        if ($tahunAjaranId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId);
        }

        return $query->with('mapel')->get()->pluck('mapel');
    }

    // public function mapelAktif()
    // {
    //     $tahunAktif = TahunAjaran::where('is_active', true)->first();
    //     return $this->belongsToMany(Mapel::class, 'guru_kelas', 'kelas_id', 'mapel_id')
    //         ->where('peran', 'pengajar')
    //         ->where('tahun_semester_id', $tahunAktif?->id);
    // }
}
