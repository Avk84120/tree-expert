<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    use HasFactory;
protected $fillable = [
        'project_id','user_id','tree_name_id','ward','tree_no','tree_name','scientific_name','family',
        'girth_cm','height_m','canopy_m','age','condition','address','landmark',
        'ownership','concern_person_name','remark','latitude','longitude',
        'accuracy','continue','photo'
    ];

// public function project()
//  { 
//     return $this->belongsTo(Project::class);
//  }

 public function project()
 {
    return $this->belongsTo(Project::class, 'project_id', 'id');
 }
public function user()
 { 
    return $this->belongsTo(User::class);
 }

 public function tree_names()
 { 
    return $this->belongsTo(TreeName::class);
 }

 public function plantation()
{
    return $this->belongsTo(Plantation::class);
}


// public function photos()
//  { 
//     return $this->morphMany(Photo::class, 'imageable');
// }

public function photos()
{
    return $this->hasMany(TreePhoto::class);
}


}