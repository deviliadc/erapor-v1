<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5ProyekDimensi extends Model
{
    protected $table = 'p5_proyek_dimensi';
    protected $fillable = ['p5_proyek_id', 'p5_dimensi_id'];

    public function proyek()
    {
        return $this->belongsTo(P5Proyek::class);
    }

    public function dimensi()
    {
        return $this->belongsTo(P5Dimensi::class);
    }
}
