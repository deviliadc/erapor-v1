<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiP5 extends Model
{
    protected $table = 'nilai_p5';
    protected $fillable = [
        'kelas_siswa_id',
        // 'siswa_id',
        // 'kelas_id',
        // 'tahun_semester_id',
        'p5_proyek_id',
        // 'rapor_id',
        // 'predikat',
        'catatan',
        // 'is_validated',
        'periode',
    ];

    // public function siswa()
    // {
    //     return $this->belongsTo(Siswa::class);
    // }

    // public function kelas()
    // {
    //     return $this->belongsTo(Kelas::class);
    // }

    // public function tahunSemester()
    // {
    //     return $this->belongsTo(TahunSemester::class);
    // }

    public function kelasSiswa()
    {
        return $this->belongsTo(KelasSiswa::class);
    }

    public function p5Proyek()
    {
        return $this->belongsTo(P5Proyek::class);
    }

    

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }
}
