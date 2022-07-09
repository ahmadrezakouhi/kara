<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['parent'];

    protected $hidden = ['pivot'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function users(){
        return $this->belongsToMany(User::class)->withPivot('owner','admin','developer');
    }

    public function parent(){
        return $this->hasOne(Project::class,'id','project_id');
    }

    public function child(){
        return $this->hasMany(Project::class,'project_id','id');
    }


    public function requirements(){
        return $this->hasMany(Requirement::class)->latest();
    }

    public function phases(){
        return $this->hasMany(Phase::class)->latest();
    }
}
