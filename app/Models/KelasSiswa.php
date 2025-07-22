<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasSiswa extends Model
{
    use HasFactory;

    protected $table = 'kelas_siswa';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tahun_semester_id',
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke Tahun Semester
    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }

    // Relasi ke Rekap Absensi
    public function rekapAbsensi()
    {
        return $this->hasOne(RekapAbsensi::class, 'siswa_id', 'siswa_id')
            ->where('tahun_semester_id', request('tahun_semester_id'))
            ->where('periode', request('periode'));
    }

    public function ekstrakurikuler()
    {
        return $this->belongsToMany(Ekstra::class, 'siswa_ekstrakurikuler')
            ->withPivot('tahun_semester_id')
            ->withTimestamps();
    }
}
