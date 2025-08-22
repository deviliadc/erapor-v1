<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapor extends Model
{

    protected $fillable = [
        'kelas_siswa_id',
        'tahun_semester_id',
        'rekap_nilai_mapel',
        'rekap_nilai_ekstra',
        'rekap_nilai_p5',
        'catatan_wali',
        'is_final',
        'tanggal_finalisasi'
    ];

    protected $casts = [
        'rekap_nilai_mapel' => 'array',
        'rekap_nilai_ekstra' => 'array',
        'rekap_nilai_p5' => 'array',
        'is_final' => 'boolean',
        'tanggal_finalisasi' => 'date',
    ];

    public function kelasSiswa()
    {
        return $this->belongsTo(KelasSiswa::class);
    }

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }

    public function rekapAbsensi()
    {
        return $this->hasOne(RekapAbsensi::class, 'kelas_siswa_id', 'kelas_siswa_id')
            ->where('tahun_semester_id', $this->tahun_semester_id);
    }
}
