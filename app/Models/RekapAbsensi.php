<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapAbsensi extends Model
{
    use HasFactory;

    protected $table = 'rekap_absensi';

    protected $fillable = [
        // 'siswa_id',
        // 'kelas_id',
        'kelas_siswa_id',
        // 'tahun_semester_id',
        'total_sakit',
        'total_izin',
        'total_alfa',
        'periode',
    ];

    public function kelasSiswa()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_siswa_id', 'kelas_id')
            ->withPivot('siswa_id')
            ->using(KelasSiswa::class);
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke tahun semester
    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }
}
