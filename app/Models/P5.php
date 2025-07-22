<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5 extends Model
{
    protected $table = 'p5';
    protected $fillable = [
        'proyek',
        'deskripsi',
        'tahun_semester_id'
    ];

    public function detail()
    {
        return $this->hasMany(DetailP5::class);
    }

    public function nilai()
    {
        return $this->hasMany(NilaiP5::class);
    }
}
