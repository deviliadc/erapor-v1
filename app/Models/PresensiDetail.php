<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiDetail extends Model
{
    use HasFactory;

    protected $table = 'presensi_detail';

    protected $fillable = [
        'presensi_harian_id',
        'kelas_siswa_id',
        'status',
        'keterangan',
        // 'periode',
    ];

    public function presensiHarian()
    {
        return $this->belongsTo(PresensiHarian::class, 'presensi_harian_id');
    }

    public function kelasSiswa()
    {
        return $this->belongsTo(KelasSiswa::class, 'kelas_siswa_id');
    }
}
