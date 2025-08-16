<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiP5Detail extends Model
{
    protected $table = 'nilai_p5_detail';
    protected $fillable = [
        'nilai_p5_id',
        'p5_sub_elemen_id',
        'p5_dimensi_id',
        // 'siswa_id',
        // 'kelas_id',
        // 'tahun_semester_id',
        // 'p5_proyek_id',
        // 'rapor_id',
        'predikat',
        'deskripsi',
        // 'periode',
        'is_validate'
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

    public function nilaiP5()
    {
        return $this->belongsTo(NilaiP5::class, 'nilai_p5_id');
    }

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }
}
