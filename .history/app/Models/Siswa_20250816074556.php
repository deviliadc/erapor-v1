<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'wali_murid_id',
        'nipd',
        'nisn',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'pendidikan_sebelumnya',
        'no_hp',
        // 'status',
        'nama_ayah',
        'nama_ibu',
        'nama_wali',
        'no_hp_wali',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'pekerjaan_wali',
        'alamat_wali',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function waliMurid()
    // {
    //     return $this->belongsTo(WaliMurid::class, 'wali_murid_id');
    // }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_siswa')
            ->withPivot('tahun_semester_id')
            ->withTimestamps();
    }

    public function kelasSiswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }

    public function kelasSiswaAktif()
    {
        $tahunAktif = TahunSemester::where('is_active', 1)->first();
        if (!$tahunAktif) return null;
        return $this->kelasSiswa()->where('tahun_semester_id', $tahunAktif->id)->first();
    }

    public function ekstra()
    {
        return $this->belongsToMany(Ekstra::class, 'siswa_ekstra')
            ->withPivot('tahun_semester_id')
            ->withTimestamps();
    }

    public function presensiDetail()
    {
        return $this->hasMany(PresensiDetail::class);
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

    public function riwayat()
    {
        return $this->hasMany(RiwayatSiswa::class);
    }
}
