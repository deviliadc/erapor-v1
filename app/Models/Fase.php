<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fase extends Model
{
    protected $table = 'fase';
    protected $fillable = [
        'nama',
        'keterangan',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'fase_id');
    }

    public function capaian()
    {
        return $this->hasMany(P5Capaian::class, 'fase_id');
    }
}
