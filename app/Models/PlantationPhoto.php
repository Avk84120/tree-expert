<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantationPhoto extends Model
{
    protected $fillable = [
        'plantation_id',
        'path',
        'caption'
    ];

    public function plantation()
    {
        return $this->belongsTo(Plantation::class);
    }
}
