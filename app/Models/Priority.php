<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function requirements(){
        return $this->hasMany(Requirement::class);
    }
}