<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cause extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function walks()
    {
        return $this->hasMany(Walk::class);
    }
}
