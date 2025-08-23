<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanRapor extends Model
{
    protected $table = 'pengaturan_rapor';
    protected $fillable = [
        'tahun_semester_id',
        'nama_kepala_sekolah',
        'nip_kepala_sekolah',
        // 'jabatan',
        'ttd',
        'tempat',
        'tanggal_cetak',
    ];

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }

    public function getTtdUrlAttribute()
    {
        return $this->ttd ? asset('storage/' . $this->ttd) : null;
    }

    // public function getTanggalCetakFormattedAttribute()
    // {
    //     return $this->tanggal_cetak ? $this->tanggal_cetak->format('d-m-Y') : '-';
    // }

    // public function getNamaKepalaSekolahAttribute()
    // {
    //     return $this->nama_kepala_sekolah ?: 'Kepala Sekolah';
    // }

    // public function getNipKepalaSekolahAttribute()
    // {
    //     return $this->nip_kepala_sekolah ?: '-';
    // }

    // public function getJabatanAttribute()
    // {
    //     return $this->jabatan ?: 'Kepala Sekolah';
    // }

    // public function getTempatAttribute()
    // {
    //     return $this->tempat ?: '-';
    // }
}
