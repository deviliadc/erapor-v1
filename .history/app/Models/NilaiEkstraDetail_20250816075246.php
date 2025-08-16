<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiEkstraDetail extends Model
{
    protected $table = 'nilai_ekstra_detail';
    protected $fillable = [
        'nilai_ekstra_id',
        'param_ekstra_id',
        'nilai',
        // 'periode',
    ];

    // public function siswa()
    // {
    //     return $this->belongsTo(Siswa::class);
    // }

    // public function ekstra()
    // {
    //     return $this->belongsTo(Ekstra::class);
    // }

    // public function kelas()
    // {
    //     return $this->belongsTo(Kelas::class);
    // }

    // public function tahunSemester()
    // {
    //     return $this->belongsTo(TahunSemester::class);
    // }
    public function nilaiEkstra()
    {
        return $this->belongsTo(NilaiEkstra::class);
    }

    public function paramEkstra()
    {
        return $this->belongsTo(ParamEkstra::class);
    }

    public function kelasSiswa()
    {
        return $this->belongsTo(KelasSiswa::class);
    }

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }
}
