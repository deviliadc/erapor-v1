<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'lampiran',
        'tanggal_mulai',
        'tanggal_berakhir',
        'dibuat_oleh',
        'ditujukan_ke',
    ];

    protected $dates = [
        'tanggal_mulai',
        'tanggal_berakhir',
    ];

    // Relasi ke pembuat pengumuman
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Aksesor: apakah pengumuman sudah kadaluarsa (diarsipkan)
    public function getSudahDiarsipAttribute(): bool
    {
        return $this->tanggal_berakhir && $this->tanggal_berakhir < Carbon::now();
    }

    // Scope: hanya pengumuman aktif
    public function scopeAktif($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('tanggal_berakhir')
                ->orWhere('tanggal_berakhir', '>=', now());
        });
    }

    // Scope: pengumuman untuk role tertentu
    public function scopeUntukRole($query, $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->where('ditujukan_ke', $role)
                ->orWhere('ditujukan_ke', 'semua')
                ->orWhereNull('ditujukan_ke');
        });
    }
}
