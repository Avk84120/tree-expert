<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    use HasFactory;
    protected $fillable = ['project_id','user_id','tree_no','common_name','scientific_name','family','girth_cm','height_m','canopy_m','age','condition','ownership','concern_person','remark','latitude','longitude','accuracy','address','landmark','tree_name_id'];


public function project()
 { 
    return $this->belongsTo(Project::class);
 }
public function user()
 { 
    return $this->belongsTo(User::class);
 }

public function photos()
 { 
    return $this->morphMany(Photo::class, 'imageable');
}

}