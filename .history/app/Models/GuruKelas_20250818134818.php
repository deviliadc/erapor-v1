<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruKelas extends Model
{
    use HasFactory;

    protected $table = 'guru_kelas';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mapel_id',
        // 'tahun_semester_id',
        'tahun_ajaran_id',
        'peran',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function lingkupMateri()
    {
        return $this->hasMany(LingkupMateri::class);
    }

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }
}
