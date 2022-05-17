<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MigController extends Controller
{
    //
   public function index(){
        $exitCode = Artisan::call('migrate:refresh', [
            '--force' => true,
        ]);
   }

}
