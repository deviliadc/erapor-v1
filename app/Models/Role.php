<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name'
    ];

    // Relasi many-to-many ke users
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
