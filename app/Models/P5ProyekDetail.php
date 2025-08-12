<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5ProyekDetail extends Model
{
    protected $table = 'p5_proyek_detail';
    protected $fillable = [
        'p5_proyek_id',
        'p5_dimensi_id',
        'p5_elemen_id',
        'p5_sub_elemen_id',
    ];

    public function proyek()
    {
        return $this->belongsTo(P5Proyek::class, 'p5_proyek_id');
    }

    public function dimensi()
    {
        return $this->belongsTo(P5Dimensi::class, 'p5_dimensi_id');
    }

    public function elemen()
    {
        return $this->belongsTo(P5Elemen::class, 'p5_elemen_id');
    }

    public function subElemen()
    {
        return $this->belongsTo(P5SubElemen::class, 'p5_sub_elemen_id');
    }
}
