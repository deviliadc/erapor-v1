<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LingkupMateri extends Model
{
    protected $table = 'lingkup_materi';

    protected $fillable = [
        'guru_kelas_id',
        'bab_id',
        'nama',
        'periode',
    ];

    public function guruKelas()
    {
        return $this->belongsTo(GuruKelas::class);
    }

    public function bab()
    {
        return $this->belongsTo(Bab::class);
    }

    public function tujuanPembelajaran()
    {
        return $this->hasMany(TujuanPembelajaran::class);
    }
}
