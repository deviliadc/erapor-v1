<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiP5 extends Model
{
    protected $table = 'nilai_p5';
    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tahun_semester_id',
        'p5_id',
        'rapor_id',
        'predikat',
        'deskripsi'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function p5()
    {
        return $this->belongsTo(P5::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }
}
