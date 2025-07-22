<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMapel extends Model
{
    protected $table = 'nilai_mapel';
    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'kelas_id',
        'tahun_semester_id',
        'rapor_id',
        'nilai',
        'kkm'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
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
