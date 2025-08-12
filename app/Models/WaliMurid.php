<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaliMurid extends Model
{
    protected $table = 'wali_murid';

    protected $fillable = [
        'nama_ayah',
        'nama_ibu',
        'nama_wali',
        'no_hp',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'pekerjaan_wali',
        'alamat',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'wali_murid_id');
    }
}
