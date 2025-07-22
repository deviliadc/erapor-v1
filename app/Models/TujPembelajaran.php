<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TujPembelajaran extends Model
{
    protected $table = 'tuj_pembelajaran';
    protected $fillable = [
        'kode',
        'tujuan',
        'mapel_id'
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}
