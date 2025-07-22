<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimensiP5 extends Model
{
    protected $table = 'p5_dimensi';
    protected $fillable = [
        'dimensi',
        'elemen',
        'subelemen',
        'capaian',
        'p5_id'
    ];

    public function p5()
    {
        return $this->belongsTo(P5::class);
    }
}
