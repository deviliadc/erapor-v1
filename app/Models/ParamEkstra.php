<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParamEkstra extends Model
{
    protected $table = 'param_ekstra';
    protected $fillable = [
        'parameter',
        'ekstra_id'
    ];

    public function ekstra()
    {
        return $this->belongsTo(Ekstra::class);
    }
}
