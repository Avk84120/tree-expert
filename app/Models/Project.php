<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name','state','client','company','field_officer','start_date','end_date','total_wards','settings'];
    protected $casts = ['settings' => 'array', 'start_date' => 'date', 'end_date' => 'date'];


    public function trees()
     {
         return $this->hasMany(Tree::class);
}

public function users()
{
    return $this->belongsToMany(User::class, 'project_user');
}

}