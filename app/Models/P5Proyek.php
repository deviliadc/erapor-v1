<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Proyek extends Model
{
    protected $table = 'p5_proyek';
    protected $fillable = [
        'nama_proyek', 'deskripsi', 'kelas_id', 'p5_tema_id','tahun_semester_id', 'guru_id',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class, 'tahun_semester_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function tema()
    {
        return $this->belongsTo(P5Tema::class, 'p5_tema_id');
    }

    public function dimensi()
    {
        return $this->belongsToMany(P5Dimensi::class, 'p5_proyek_dimensi', 'p5_proyek_id', 'p5_dimensi_id');
    }

    public function elemen()
    {
        return $this->belongsToMany(P5Elemen::class, 'p5_proyek_elemen', 'p5_proyek_id', 'p5_elemen_id');
    }

    public function subElemen()
    {
        return $this->belongsToMany(P5SubElemen::class, 'p5_proyek_sub_elemen', 'p5_proyek_id', 'p5_sub_elemen_id');
    }

    public function dokumentasi()
    {
        return $this->hasMany(P5Dokumentasi::class);
    }

    public function proyekDimensi()
    {
        return $this->hasMany(P5ProyekDimensi::class, 'p5_proyek_dimensi_id');
    }

    public function proyekSubElemen()
    {
        return $this->hasMany(P5ProyekSubElemen::class, 'p5_proyek_sub_elemen_id');
    }
}
