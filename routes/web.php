<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MigController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    
    Route::resource('/personnel', PersonnelController::class);
    Route::delete('/personnel/{id}', [PersonnelController::class, "destroy"]);
    Route::post('/personnel/getData', [PersonnelController::class, "getData"]);

    Route::resource('/user', UserController::class);
    Route::delete('/user/{id}', [UserController::class, "destroy"]);
    Route::post('/user/getAll', [UserController::class, "getAll"]);
    Route::post('/user/getData', [UserController::class, "getData"]);
    Route::post('/user/getDataForProject', [UserController::class, "getDataForProject"]);
    Route::post('/user/addUser', [UserController::class, "addUser"]);

    Route::resource('/project', ProjectController::class);
    Route::delete('/project/{id}', [ProjectController::class, "destroy"]);
    Route::post('/project/getData', [ProjectController::class, "getData"]);
    Route::post('/project/getProjects', [ProjectController::class, "getProjects"]); 
    Route::post('/project/addParent', [ProjectController::class, "addParent"]);
    Route::post('/project/addProject', [ProjectController::class, "addProject"]);
    
    Route::resource('/projectUser', ProjectUserController::class);
    Route::delete('/projectUser/{id}', [ProjectUserController::class, "destroy"]);
    Route::post('/projectUser/getData', [ProjectUserController::class, "getData"]);
    Route::post('/projectUser/add', [ProjectUserController::class, "add"]);
    Route::get('/projectUser/getAllWithID/{id}', [ProjectUserController::class, "getAllWithID"]);
    Route::post('/projectUser/getUserByParentProject', [ProjectUserController::class, "getUserByParentProject"]);
    Route::post('/projectUser/getUserProjects', [ProjectUserController::class, "getUserProjects"]);

    Route::resource('/task', TaskController::class);
    Route::delete('/task/{id}', [TaskController::class, "destroy"]);
    Route::post('/task/getData', [TaskController::class, "getData"]);
    Route::post('/task/addTask', [TaskController::class, "addTask"]);
    Route::post('/task/setDoneTask', [TaskController::class, "setDoneTask"]);

    Route::resource('/mig', MigController::class);
});

// Route::get('/project', function () {
//     return view('project');
// })->middleware(['auth'])->name('project');

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
