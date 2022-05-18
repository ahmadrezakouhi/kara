<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Auth;

class MigController extends Controller
{
    //
   public function index(){
    if(Gate::check('isAdmin') || Gate::check('isManager')){
        $exitCode = Artisan::call('migrate:refresh', [
            '--force' => true,
            '--seed'  => true
        ]);
    }
   }

}
