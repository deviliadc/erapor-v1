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
        'nilai_akhir',
        'nilai_akhir_ekstra',
        'nilai_akhir_p5',
        'catatan',
        'is_validated',
        'validated_at',
        'validated_by',
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
