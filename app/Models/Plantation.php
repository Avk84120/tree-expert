<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantation extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'name',
        'area_coordinates',  // JSON field (lat/long points)
        'total_trees',
    ];

    protected $casts = [
        'area_coordinates' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function trees()
    {
        return $this->belongsToMany(Tree::class, 'plantation_tree');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }
}
