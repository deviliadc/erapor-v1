<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TujuanPembelajaran extends Model
{
    protected $table = 'tujuan_pembelajaran';
    protected $fillable = [
        'subbab',
        'tujuan',
        'lingkup_materi_id'
    ];

    public function lingkupMateri()
    {
        return $this->belongsTo(LingkupMateri::class);
    }

    // Relasi tidak langsung, via accessor
    public function getKelasAttribute()
    {
        return optional($this->lingkupMateri?->guruKelas?->kelas);
    }

    public function getMapelAttribute()
    {
        return optional($this->lingkupMateri?->guruKelas?->mapel);
    }

    public function getBabAttribute()
    {
        return optional($this->lingkupMateri?->bab);
    }
}
