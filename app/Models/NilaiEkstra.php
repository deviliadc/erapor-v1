<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiEkstra extends Model
{
    protected $table = 'nilai_ekstra';
    protected $casts = [
    'param_nilai' => 'array',
    'predikat_param' => 'array',
];
    protected $fillable = [
        'kelas_siswa_id',
        // 'siswa_id',
        'ekstra_id',
        // 'kelas_id',
        // 'tahun_semester_id',
        'rapor_id',
        'predikat',
        'deskripsi'
    ];

    // public function siswa()
    // {
    //     return $this->belongsTo(Siswa::class);
    // }

    public function ekstra()
    {
        return $this->belongsTo(Ekstra::class);
    }

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

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }
}
