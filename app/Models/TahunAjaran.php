<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun',
        // 'mulai',
        // 'selesai',
        'is_active',
    ];

    public function tahunSemester()
    {
        return $this->hasMany(TahunSemester::class);
    }

    protected static function booted()
{
    static::saving(function ($tahun) {
        if ($tahun->is_active) {
            // Nonaktifkan semua tahun ajaran lain
            self::where('id', '!=', $tahun->id)->update(['is_active' => false]);
        }
    });
}

}
