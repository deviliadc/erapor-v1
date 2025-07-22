<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bab extends Model
{
    protected $table = 'bab';
    protected $fillable = [
        'nama',
        'mapel_id'
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}
