<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name','state_id','city_id','client','company','field_officer','total_count',
    'ward','start_date','end_date','total_wards','settings'];
    protected $casts = ['settings' => 'array', 'start_date' => 'date', 'end_date' => 'date'];


//     public function trees()
//      {
//          return $this->hasMany(Tree::class);
// }
public function trees()
{
    return $this->hasMany(Tree::class, 'project_id', 'id');
}


// public function users()
// {
//     return $this->belongsToMany(User::class, 'project_user');
// }

public function users()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')
                    ->withTimestamps();
    }

public function state()
{
    return $this->belongsTo(State::class);
}

public function city()
{
    return $this->belongsTo(City::class);
}


}