<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreePhoto extends Model
{
    use HasFactory;

    protected $table = 'tree_photos'; // ensure correct table

    protected $fillable = [
        'tree_id',
        'path',
        'latitude',
        'longitude',
        'accuracy',
    ];

    public function tree()
    {
        return $this->belongsTo(Tree::class, 'tree_id');
    }
}
