<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plantation extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'area_coordinates',
        'total_trees'
    ];

    public function trees()
    {
        return $this->hasMany(PlantationTree::class);
    }

    public function photos()
    {
        return $this->hasMany(PlantationPhoto::class);
    }
}
