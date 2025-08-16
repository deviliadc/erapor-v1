<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasSiswa extends Model
{
    use HasFactory;

    protected $table = 'kelas_siswa';

    protected $fillable = [
        'no_absen',
        'siswa_id',
        'kelas_id',
        // 'tahun_semester_id',
        'tahun_ajaran_id',
        'status',
        // 'tanggal_masuk',
        // 'tanggal_keluar',
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke Tahun Semester
    // public function tahunSemester()
    // {
    //     return $this->belongsTo(TahunSemester::class);
    // }
    
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // Relasi ke Rekap Absensi
    public function rekapAbsensi()
    {
        return $this->hasOne(RekapAbsensi::class, 'siswa_id', 'siswa_id')
            ->where('tahun_semester_id', request('tahun_semester_id'))
            ->where('periode', request('periode'));
    }

    public function ekstrakurikuler()
    {
        return $this->belongsToMany(Ekstra::class, 'siswa_ekstrakurikuler')
            ->withPivot('tahun_semester_id')
            ->withTimestamps();
    }

    // Event booting
    // protected static function boot()
    // {
    //     parent::boot();

    //     // Saat siswa baru ditempatkan di kelas
    //     static::created(function ($kelasSiswa) {
    //         RiwayatSiswa::create([
    //             'siswa_id' => $kelasSiswa->siswa_id,
    //             'kelas_id' => $kelasSiswa->kelas_id,
    //             'tahun_semester_id' => $kelasSiswa->tahun_semester_id,
    //             'status' => 'Aktif',
    //             'tanggal_masuk' => now(),
    //             'keterangan' => 'Penempatan kelas awal',
    //         ]);
    //     });

    //     // Saat siswa pindah kelas
    //     static::updating(function ($kelasSiswa) {
    //         $riwayatLama = RiwayatSiswa::where('siswa_id', $kelasSiswa->siswa_id)
    //             ->whereNull('tanggal_keluar')
    //             ->latest('tanggal_masuk')
    //             ->first();

    //         if ($riwayatLama) {
    //             $riwayatLama->update([
    //                 'status' => 'Mutasi',
    //                 'tanggal_keluar' => now(),
    //                 'keterangan' => 'Pindah kelas',
    //             ]);
    //         }

    //         // Tambah riwayat baru
    //         RiwayatSiswa::create([
    //             'siswa_id' => $kelasSiswa->siswa_id,
    //             'kelas_id' => $kelasSiswa->kelas_id,
    //             'tahun_semester_id' => $kelasSiswa->tahun_semester_id,
    //             'status' => 'Aktif',
    //             'tanggal_masuk' => now(),
    //             'keterangan' => 'Pindah ke kelas baru',
    //         ]);
    //     });

    //     // Saat siswa dikeluarkan dari kelas (lulus / keluar / mutasi)
    //     static::deleted(function ($kelasSiswa) {
    //         RiwayatSiswa::where('siswa_id', $kelasSiswa->siswa_id)
    //             ->whereNull('tanggal_keluar')
    //             ->update([
    //                 'status' => 'Keluar', // default, nanti bisa diganti lulus/mutasi
    //                 'tanggal_keluar' => now(),
    //                 'keterangan' => 'Dihapus dari kelas',
    //             ]);
    //     });
    // }
}
