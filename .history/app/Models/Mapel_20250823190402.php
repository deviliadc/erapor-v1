<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapel';

    protected $fillable = [
        'kode_mapel',
        'nama',
        'kategori',
        'urutan'
    ];

    // public function bab()
    // {
    //     return $this->hasMany(Bab::class);
    // }

    public function tujuanPembelajaran()
    {
        return $this->hasMany(TujuanPembelajaran::class);
    }

    public function lingkupMateri()
    {
        return $this->hasMany(LingkupMateri::class);
    }

    public function guruKelas()
    {
        return $this->hasMany(GuruKelas::class);
    }

    public function nilaiMapel()
    {
        return $this->hasMany(NilaiMapel::class);
    }

}
