<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Dokumentasi extends Model
{
    protected $table = 'p5_dokumentasi';
    protected $fillable = ['p5_proyek_id', 'file_path', 'keterangan'];

    public function proyek()
    {
        return $this->belongsTo(P5Proyek::class);
    }
}
