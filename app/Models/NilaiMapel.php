<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMapel extends Model
{
    protected $table = 'nilai_mapel';
    protected $fillable = [
        'kelas_siswa_id',
        // 'siswa_id',
        'mapel_id',
        // 'tahun_semester_id',
        'nilai_akhir',
        'deskripsi_tertinggi',
        'deskripsi_terendah',
    ];

    public function kelasSiswa()
    {
        return $this->belongsTo(KelasSiswa::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }

    // public function detail()
    // {
    //     return $this->hasMany(NilaiMapelDetail::class, 'siswa_id', 'siswa_id')
    //         ->whereColumn('nilai_mapel_detail.mapel_id', 'nilai_mapel.mapel_id')
    //         ->whereColumn('nilai_mapel_detail.tahun_semester_id', 'nilai_mapel.tahun_semester_id');
    // }

    public function detail()
    {
        return $this->hasMany(NilaiMapelDetail::class, 'nilai_mapel_id', 'id');
    }
}
