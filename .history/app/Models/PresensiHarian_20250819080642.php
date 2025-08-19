<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiHarian extends Model
{
    use HasFactory;

    protected $table = 'presensi_harian';

    protected $fillable = [
        'kelas_id',
        'tanggal',
        // 'penginput_id',
        'catatan',
        'periode',
    ];

    public function kelasSiswa()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_siswa_id', 'kelas_id')
                    ->withPivot('siswa_id')
                    ->using(KelasSiswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    

    public function penginput()
    {
        return $this->belongsTo(User::class, 'penginput_id');
    }

    public function detail()
    {
        return $this->hasMany(PresensiDetail::class, 'presensi_harian_id');
    }
}
