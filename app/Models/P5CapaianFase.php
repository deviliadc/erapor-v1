<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5CapaianFase extends Model
{
    protected $table = 'p5_capaian_fase';
    protected $fillable = [
        'fase_id',
        'p5_sub_elemen_id',
        'capaian',
    ];

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }

    public function subElemen()
    {
        return $this->belongsTo(P5SubElemen::class, 'p5_sub_elemen_id');
    }

    // public function proyekDetail()
    // {
    //     return $this->hasMany(P5ProyekDetail::class, 'p5_capaian_fase_id');
    // }

    public function nilaiP5Detail()
    {
        return $this->hasMany(NilaiP5Detail::class, 'p5_capaian_fase_id');
    }
}
