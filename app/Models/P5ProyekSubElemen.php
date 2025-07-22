<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5ProyekSubElemen extends Model
{
    protected $table = 'p5_proyek_sub_elemen';
    protected $fillable = ['p5_proyek_id', 'p5_sub_elemen_id'];

    public function proyek()
    {
        return $this->belongsTo(P5Proyek::class);
    }

    public function subElemen()
    {
        return $this->belongsTo(P5SubElemen::class);
    }
}
