<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantationTree extends Model
{
    protected $fillable = [
        'plantation_id',
        'tree_id',
        'count'
    ];

    public function plantation()
    {
        return $this->belongsTo(Plantation::class);
    }

    public function tree()
    {
        return $this->belongsTo(TreeName::class, 'tree_id');
    }
}
