<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailP5 extends Model
{
    protected $table = 'detail_p5';
    protected $fillable = [
        'dimensi',
        'elemen',
        'subelemen',
        'p5_id'
    ];

    public function p5()
    {
        return $this->belongsTo(P5::class);
    }
}
