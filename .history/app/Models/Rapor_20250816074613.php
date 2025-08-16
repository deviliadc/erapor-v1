<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapor extends Model
{
    protected $table = 'rapor';
    protected $fillable = [
        
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
