<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesMapel extends Model
{
    protected $table = 'des_mapel';
    protected $fillable = [
        'nilai_min',
        'nilai_max',
        'des_min',
        'des_max',
        'mapel_id'];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}
