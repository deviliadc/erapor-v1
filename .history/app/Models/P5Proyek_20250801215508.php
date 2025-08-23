<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Proyek extends Model
{
    protected $table = 'p5_proyek';
    protected $fillable = [
        'nama_proyek',
        'deskripsi',
        // 'kelas_id',
        // 'p5_tema_id',
        'tahun_semester_id',
        // 'guru_id',
    ];

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class, 'tahun_semester_id');
    }

    public function tema()
    {
        return $this->belongsTo(P5Tema::class, 'p5_tema_id');
    }

    public function detail()
    {
        return $this->hasMany(P5ProyekDetail::class, 'p5_proyek_id');
    }
}
