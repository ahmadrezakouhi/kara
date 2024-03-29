<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'mobile',
        'phone',
        'email',
        'user_id',
        'password',
        'background_color',
        'text_color'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function projects(){
        return $this->belongsToMany(Project::class)->withPivot('owner','admin','developer');
    }



    public function isAdmin(){
        return $this->role == 'admin';
    }


    public function isUser(){
        return $this->role == 'user';
    }

    public function tasks(){
        return $this->hasMany(Task::class)->latest();
    }


    public function sprints(){
        return $this->hasMany(Sprint::class);
    }


    public function phases(){
        return $this->hasMany(Phase::class);
    }
}
