<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunSemester extends Model
{
    use HasFactory;

    protected $table = 'tahun_semester';

    protected $fillable = [
        'tahun_ajaran_id',
        // 'tahun',
        'semester',
        'is_active',
    ];

    public

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function kelasSiswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }

    public function isUTSValidated()
    {
        return $this->is_validated_uts;
    }

    public function isUASValidated()
    {
        return $this->is_validated_uas;
    }
}
