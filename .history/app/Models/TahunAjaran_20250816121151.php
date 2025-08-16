<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun',
        'nama',
        'is_active',
    ];

    public function tahunSemester()
    {
        return $this->hasMany(TahunSemester::class);
    }
}
