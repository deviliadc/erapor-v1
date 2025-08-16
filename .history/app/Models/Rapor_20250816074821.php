<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapor extends Model
{
    protected $table = 'rapor';
    protected $fillable = [
        'kelas_siswa_id',
        // 'tahun_semester_id',
        // 'siswa_id',
        'rekap_nilai_mapel',
        'rekap_nilai_ekstra',
        'rekap_nilai_p5',
        'catatan_wali',
        'jumlah_sakit',
        'jumlah_izin',
        'jumlah_alpha',
        'is_final',
        'tanggal_finalisasi',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function nilaiMapel()
    {
        return $this->hasMany(NilaiMapel::class);
    }

    public function nilaiEkstra()
    {
        return $this->hasMany(NilaiEkstra::class);
    }

    public function nilaiP5()
    {
        return $this->hasMany(NilaiP5::class);
    }
}
