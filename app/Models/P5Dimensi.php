<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Dimensi extends Model
{
    protected $table = 'p5_dimensi';
    protected $fillable = ['nama_dimensi', 'deskripsi'];

    public function elemen()
    {
        return $this->hasMany(P5Elemen::class);
    }
}
