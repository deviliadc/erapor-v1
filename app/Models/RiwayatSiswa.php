<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatSiswa extends Model
{
    protected $table = 'riwayat_siswa';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tahun_semester_id',
        'status',
        'tanggal_masuk',
        'tanggal_keluar',
        'keterangan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }
}
