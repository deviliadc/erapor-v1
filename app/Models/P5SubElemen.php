<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5SubElemen extends Model
{
    protected $table = 'p5_sub_elemen';
    protected $fillable = [
        'p5_elemen_id',
        'nama_sub_elemen',
        // 'deskripsi'
    ];

    public function elemen()
    {
        return $this->belongsTo(P5Elemen::class, 'p5_elemen_id');
    }

    public function capaianFase()
    {
        return $this->hasMany(P5CapaianFase::class, 'p5_sub_elemen_id');
    }

    public function proyekDetail()
    {
        return $this->hasMany(P5ProyekDetail::class, 'p5_sub_elemen_id');
    }
}
