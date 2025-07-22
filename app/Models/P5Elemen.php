<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Elemen extends Model
{
    protected $table = 'p5_elemen';
    protected $fillable = ['p5_dimensi_id', 'nama_elemen', 'deskripsi'];

    public function dimensi()
    {
        return $this->belongsTo(P5Dimensi::class, 'p5_dimensi_id');
    }

    public function subElemen()
    {
        return $this->hasMany(P5SubElemen::class);
    }
}
