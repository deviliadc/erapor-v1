<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekstra extends Model
{
    use HasFactory;
    protected $table = 'ekstra';
    protected $fillable = ['nama'];

    public function paramEkstra()
    {
        return $this->hasMany(ParamEkstra::class);
    }

    // public function siswa()
    // {
    //     return $this->belongsToMany(Siswa::class, 'siswa_ekstra')
    //         ->withPivot('tahun_semester_id')
    //         ->withTimestamps();
    // }

    public function nilai()
    {
        return $this->hasMany(NilaiEkstra::class);
    }

    public function siswa()
{
    return $this->belongsToMany(KelasSiswa::class, 'siswa_ekstra')
                ->withPivot('tahun_semester_id')
                ->withTimestamps();
}

}
