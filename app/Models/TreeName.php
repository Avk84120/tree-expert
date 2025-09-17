<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreeName extends Model
{
    use HasFactory;
    protected $fillable = [
        'common_name',
        'scientific_name',
        'family',
    ];

    public function trees()
    {
        return $this->hasMany(Tree::class);
    }

    // TreeName.php
public function plantations()
{
    return $this->belongsToMany(Plantation::class, 'plantation_trees')
                ->withPivot('count')->withTimestamps();
}

}
