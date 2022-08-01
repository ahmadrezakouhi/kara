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
use App\Models\Task;
use App\Models\Phase;
use App\Models\Requirement;
use App\Models\Sprint;
use GuzzleHttp\Middleware;

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
    return redirect('login');
});



Route::middleware('auth', 'role:admin')->group(function () {



    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/{user?}',[UserController::class , 'store'])->name('users.store');
        Route::get('/{user}',[UserController::class,'edit'])->name('users.edit');
        Route::delete('{user}',[UserController::class,'destroy'])->name('users.destroy');
    });
});




Route::prefix('project')->middleware('auth')->group(function () {

    Route::get('/', [ProjectController::class, 'index'])
        ->name('projects.index');

    Route::post('{project?}', [ProjectController::class, 'store'])
        ->name('projects.store');

    Route::get('getUsers', [ProjectController::class, 'getUsers'])
        ->name('projects.getUsers');


    Route::get('getProjects', [ProjectController::class, 'getProjects'])
        ->name('projects.getProjects');

    Route::get('{project}/getProjectUsers', [ProjectController::class, 'getProjectUsers'])
        ->name('projects.getProjectUsers')->can('getProjectUsers', \App\Models\Project::class);

    Route::post('{project}/add-users', [ProjectController::class, 'addUsers'])
        ->name('projects.addUsers')->can('addUsers', \App\Models\Project::class);

    Route::get('{project}', [ProjectController::class, 'edit'])
        ->name('projects.edit')->can('update', \App\Models\Project::class);

    Route::delete('{project}', [ProjectController::class, 'destroy'])
        ->name('projects.destroy')->can('delete', 'project');
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



Route::prefix('projects')->middleware('auth')->group(function () {









    Route::get('/{id}/requirements', [RequirementController::class, 'index'])
        ->name('projects.requirements.index')->can('viewAny', [Requirement::class, 'id']);

    Route::post('/requirements/{id?}', [RequirementController::class, 'store'])
        ->name('projects.requirements.store');

    Route::delete('/requirements/{requirement}', [RequirementController::class, 'destroy'])
        ->name('projects.requirements.destroy')->can('delete', 'requirement');;

    Route::get('/requirements/{requirement}', [RequirementController::class, 'edit'])
        ->name('projects.requirements.edit')->can('update', 'requirement');




    Route::get('/{id}/phases', [PhaseController::class, 'index'])
        ->name('projects.phases.index')->can('viewAny', [Phase::class, 'id']);

    Route::post('/phases/{id?}', [PhaseController::class, 'store'])
        ->name('projects.phases.store');

    Route::delete('/phases/{phase}', [PhaseController::class, 'destroy'])
        ->name('projects.phases.destroy')->can('delete', 'phase');

    Route::get('/phases/{phase}', [PhaseController::class, 'edit'])
        ->name('projects.phases.edit')->can('update', 'phase');
});


Route::prefix('requirements')->middleware('auth')->group(function () {

    Route::post('/{requirement}/phases', [RequirementController::class, 'add_phase'])
        ->name('requirements.phases.store')->can('add_phase', 'requirement');
});


Route::prefix('phases')->middleware('auth')->group(function () {

    Route::get('/{id}/sprints', [SprintController::class, 'index'])
        ->name('phases.sprints.index')->can('viewAny', [Sprint::class, 'id']);

    Route::post('/sprints/{id?}', [SprintController::class, 'store'])
        ->name('phases.sprints.store');

    Route::delete('/sprints/{sprint}', [SprintController::class, 'destroy'])
        ->name('phases.sprints.destroy')->can('delete', 'sprint');

    Route::get('/sprints/{sprint}', [SprintController::class, 'edit'])
        ->name('phases.sprints.edit')->can('update', 'sprint');
});

Route::prefix('sprints')->middleware('auth')->group(function () {

    Route::get('/{id}/tasks', [TaskController::class, 'index'])
        ->name('sprints.tasks.index')->can('viewAny', [Task::class, 'id']);

    Route::post('/tasks/{task?}', [TaskController::class, 'store'])
        ->name('sprints.tasks.store');

    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
        ->name('sprints.tasks.destroy')->can('delete', 'task');

    Route::get('/tasks/{task}', [TaskController::class, 'edit'])
        ->name('sprints.tasks.edit')->can('update', 'task');
});

Route::prefix('tasks')->middleware('auth')->group(function () {

    Route::get('/', [TaskController::class, 'owner'])->name('tasks.owner');

    Route::get('task-board', [TaskController::class, 'taskBoard'])
        ->name('tasks.task-board');

    Route::post('/{task}/change-status', [TaskController::class, 'changeStatus'])
        ->name('tasks.change-status')->can('changeStatus', 'task');

    Route::post('/{task}/play-pause', [TaskController::class, 'playPause'])
        ->name('tasks.play-pause')->can('playPause', 'task');

    Route::post('/{task}/accept', [TaskController::class, 'accept'])->name('tasks.accept')
    ->can('confirmTask','task');

    Route::post('/{task}/addComment',[TaskController::class,'addComment'])->name('tasks.addComment')
    ->can('addComment','task');
});


Route::prefix('sprints')->middleware('auth')->group(function () {
    Route::get('/', [SprintController::class, 'owner'])->name('sprints.owner');
});

Route::prefix('phases')->middleware('auth')->group(function () {
    Route::get('/', [PhaseController::class, 'owner'])->name('phases.owner');
});
