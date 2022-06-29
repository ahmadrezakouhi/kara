<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function times(){
        return $this->hasMany(Time::class);
    }
}
