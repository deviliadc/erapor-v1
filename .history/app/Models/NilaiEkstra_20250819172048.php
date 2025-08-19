<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiEkstra extends Model
{
    protected $table = 'nilai_ekstra';
    protected $fillable = [
        'kelas_siswa_id',
        // 'siswa_id',
        'ekstra_id',
        // 'kelas_id',
        'tahun_semester_id',
        // 'rapor_id',
        'periode',
        'nilai_akhir',
        'deskripsi'
    ];

    // public function siswa()
    // {
    //     return $this->belongsTo(Siswa::class);
    // }

    public function kelasSiswa()
    {
        return $this->belongsTo(KelasSiswa::class);
    }

    public function ekstra()
    {
        return $this->belongsTo(Ekstra::class);
    }

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }

    public function detailEkstra()
    {
        return $this->hasMany(NilaiEkstraDetail::class);
    }

    // public function kelas()
    // {
    //     return $this->belongsTo(Kelas::class);
    // }

    // public function tahunSemester()
    // {
    //     return $this->belongsTo(TahunSemester::class);
    // }

    public function rapor()
    {
        return $this->belongsTo(Rapor::class);
    }

        <?php
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($nilai) {
            $kelasSiswa = $nilai->kelasSiswa;
            $tahunSemester = $nilai->tahunSemester;

            if (!$kelasSiswa || !$tahunSemester) {
                throw new \Exception("Data kelas siswa atau tahun semester tidak ditemukan.");
            }

            if ($kelasSiswa->tahun_ajaran_id !== $tahunSemester->tahun_ajaran_id) {
                throw new \Exception("Tahun ajaran tidak konsisten.");
            }
        });
    }
}
