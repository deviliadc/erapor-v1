<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapel';

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
    ];

    public function bab()
    {
        return $this->hasMany(Bab::class);
    }

    public function tujuanPembelajaran()
    {
        return $this->hasMany(TujPembelajaran::class);
    }

    public function deskripsi()
    {
        return $this->hasOne(DesMapel::class);
    }

    public function nilaiMapel()
    {
        return $this->hasMany(NilaiMapel::class);
    }

}
