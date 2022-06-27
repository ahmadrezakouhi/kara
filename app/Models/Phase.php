<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function sprints(){
        return $this->hasMany(Sprint::class)->latest();
    }
}
