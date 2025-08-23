<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidasiSemester extends Model
{
    protected $table = 'validasi_semester';

    protected $fillable = [
        'tahun_semester_id',
        'tipe',
        'is_validated',
        'validated_at',
        'validated_by',
    ];

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'validated_by');
    // }

    public function validator()
    {
            return $this->belongsTo(User::class, 'validated_by');
    }

}
