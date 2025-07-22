<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama',
        'tahun_semester_id',
        'guru_id',
    ];

    public function siswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
