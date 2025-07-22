<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekstra extends Model
{
    protected $table = 'ekstra';
    protected $fillable = ['nama'];

    public function parameter()
    {
        return $this->hasMany(ParamEkstra::class);
    }

    public function nilai()
    {
        return $this->hasMany(NilaiEkstra::class);
    }
}
