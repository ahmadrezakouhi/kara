<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserController;
use App\Http\Controllers\MigController;
use App\Http\Controllers\TaskTitleController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\TaskController;

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
    // Route::delete('/personnel/{personnel}', [PersonnelController::class, "destroy"]);
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
    Route::post('/project/getParentProjects', [ProjectController::class, "getParentProjects"]);
    Route::post('/project/addParent', [ProjectController::class, "addParent"]);
    Route::post('/project/addProject', [ProjectController::class, "addProject"]);


    Route::resource('/projectUser', ProjectUserController::class);
    Route::prefix("projectUser")->group(function () {
        Route::delete('{id}', [ProjectUserController::class, "destroy"]);
        Route::post('getData', [ProjectUserController::class, "getData"]);
        Route::post('add', [ProjectUserController::class, "add"]);
        Route::get('getAllWithID/{id}', [ProjectUserController::class, "getAllWithID"],);
        Route::post('getUserByParentProject', [ProjectUserController::class, "getUserByParentProject"]);
        Route::post('getUserProjects', [ProjectUserController::class, "getUserProjects"]);
    });


    Route::resource('/task', TaskController::class);
    Route::delete('/task/{id}', [TaskController::class, "destroy"]);
    Route::post('/task/getData', [TaskController::class, "getData"]);
    Route::post('/task/addTask', [TaskController::class, "addTask"]);
    Route::post('/task/setDoneTask', [TaskController::class, "setDoneTask"]);

    Route::resource('/taskTitle', TaskTitleController::class);
    Route::delete('/taskTitle/{id}', [TaskTitleController::class, "destroy"]);
    Route::post('/taskTitle/getData', [TaskTitleController::class, "getData"]);
    Route::post('/taskTitle/addTaskTitle', [TaskTitleController::class, "addTaskTitle"]);
    Route::post('/taskTitle/getTaskTitles', [TaskTitleController::class, "getTaskTitles"]);

    Route::resource('/mig', MigController::class);
});

// Route::get('/project', function () {
//     return view('project');
// })->middleware(['auth'])->name('project');

require __DIR__ . '/auth.php';

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::prefix('projects')->group(function () {

    Route::get('/{id}/requirements', [RequirementController::class, 'index'])
        ->name('projects.requirements.index');

    Route::post('/requirements/{id?}', [RequirementController::class, 'store'])
        ->name('projects.requirements.store');

    Route::delete('/requirements/{id}', [RequirementController::class, 'destroy'])
        ->name('projects.requirements.destroy');

    Route::get('/requirements/{id}', [RequirementController::class, 'edit'])
        ->name('projects.requirements.edit');


    Route::get('/{id}/phases', [PhaseController::class, 'index'])
        ->name('projects.phases.index');

    Route::post('/phases/{id?}', [PhaseController::class, 'store'])
        ->name('projects.phases.store');

    Route::delete('/phases/{id}', [PhaseController::class, 'destroy'])
        ->name('projects.phases.destroy');

    Route::get('/phases/{id}', [PhaseController::class, 'edit'])
        ->name('projects.phases.edit');
});


Route::prefix('requirements')->group(function () {

    Route::post('/{id?}/phases', [RequirementController::class, 'add_phase'])
        ->name('requirements.phases.store');
});


Route::prefix('phases')->group(function () {

    Route::get('/{id}/sprints', [SprintController::class, 'index'])
        ->name('phases.sprints.index');

    Route::post('/sprints/{id?}', [SprintController::class, 'store'])
        ->name('phases.sprints.store');

    Route::delete('/sprints/{id}', [SprintController::class, 'destroy'])
        ->name('phases.sprints.destroy');

    Route::get('/sprints/{id}', [SprintController::class, 'edit'])
        ->name('phases.sprints.edit');
});

Route::prefix('sprints')->group(function () {

    Route::get('/{id}/tasks', [TaskController::class, 'index'])
        ->name('sprints.tasks.index');

    Route::post('/tasks/{id?}', [TaskController::class, 'store'])
        ->name('sprints.tasks.store');

    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])
        ->name('sprints.tasks.destroy');

    Route::get('/tasks/{id}', [TaskController::class, 'edit'])
        ->name('sprints.tasks.edit');
});

Route::prefix('tasks')->group(function () {
    Route::get('task-board',[TaskController::class,'taskBoard'])
    ->name('tasks.task-borad');

    Route::post('change-status',[TaskController::class,'changeStatus'])
    ->name('tasks.change-status');
});
