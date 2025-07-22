<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaEkstra extends Model
{
    use HasFactory;

    protected $table = 'siswa_ekstra';

    protected $fillable = [
        // 'siswa_id',
        'kelas_id',
        'ekstra_id',
        'tahun_semester_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function ekstra()
    {
        return $this->belongsTo(Ekstra::class);
    }

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }
}
