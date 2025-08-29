<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMapel extends Model
{
    protected $table = 'nilai_mapel';
    protected $fillable = [
        'kelas_siswa_id',
        // 'siswa_id',
        'mapel_id',
        'tahun_semester_id',
        'nilai_akhir',
        'deskripsi_tertinggi',
        'deskripsi_terendah',
        'periode',
        // 'is_validated',
    ];

    public function kelasSiswa()
    {
        return $this->belongsTo(KelasSiswa::class);
    }

    // public function siswa()
    // {
    //     return $this->belongsTo(Siswa::class);
    // }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function tahunSemester()
    {
        return $this->belongsTo(TahunSemester::class);
    }

    public function detailMapel()
    {
        return $this->hasMany(NilaiMapelDetail::class, 'nilai_mapel_id', 'id');
    }

    /** Accessors & Mutators */
    public function getNilaiAkhirAttribute($value)
    {
        return $value !== null ? round($value) : null;
    }

    public function setNilaiAkhirAttribute($value)
    {
        $this->attributes['nilai_akhir'] = $value !== null ? round($value) : null;
    }

    /** Scopes */
    public function scopeSemester($query, $semester)
    {
        return $query->whereHas('tahunSemester', function ($q) use ($semester) {
            $q->where('semester', ucfirst(strtolower($semester)));
        });
    }

    public function scopePeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }

    /** Boot */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($nilai) {
            $kelasSiswa = $nilai->kelasSiswa;
            $tahunSemester = $nilai->tahunSemester;

            if ($kelasSiswa && $tahunSemester) {
                if ($kelasSiswa->tahun_ajaran_id !== $tahunSemester->tahun_ajaran_id) {
                    throw new \Exception("Tahun ajaran tidak konsisten.");
                }
            }
        });
    }
}
