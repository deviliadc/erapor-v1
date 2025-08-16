<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMapelDetail extends Model
{
    protected $table = 'nilai_mapel_detail';
    protected $fillable = [
        'nilai_mapel_id',
        // 'siswa_id',
        // 'mapel_id',
        // 'tahun_semester_id',
        'lingkup_materi_id',
        'tujuan_pembelajaran_id',
        'jenis_nilai',
        'nilai',
        // 'periode',
        // 'is_validated'
    ];

    public function nilaiMapel()
    {
        return $this->belongsTo(NilaiMapel::class);
    }

    public function tujuanPembelajaran()
    {
        return $this->belongsTo(TujuanPembelajaran::class);
    }

    public function lingkupMateri()
    {
        return $this->belongsTo(LingkupMateri::class);
    }
}
