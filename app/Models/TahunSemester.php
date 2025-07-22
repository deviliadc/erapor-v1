<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunSemester extends Model
{
    use HasFactory;

    protected $table = 'tahun_semester';

    protected $fillable = [
        'tahun',       // contoh: "2024/2025"
        'semester',    // contoh: "Ganjil" / "Genap"
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function kelasSiswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }
}
