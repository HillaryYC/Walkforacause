<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Walk extends Model
{
    protected $fillable = [
        'user_id',
        'cause_id',
        'walked_on',
        'distance_km',
    ];

    protected $casts = [
        'walked_on' => 'date',
        'distance_km' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }
}
