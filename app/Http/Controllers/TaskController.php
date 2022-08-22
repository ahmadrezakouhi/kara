<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $sprint_id)
    {

        $sprint = Sprint::with('tasks.category')->findOrFail($sprint_id);
        $users = $sprint->phase->project->users;
        $categories = Category::all();
        if ($request->ajax()) {
            $tasks = $sprint->tasks;
            $recordTotal = $tasks->count();
            $data = array(
                "draw" => intval($request['draw']),
                "recordsTotal" => intval($recordTotal),
                "recordsFiltered" => intval(2),
                "data" => $tasks
            );
            return response()->json($data);
        }
        return view('task.index', compact('sprint', 'categories', 'users'));
    }



    public function owner(Request $request)
    {
        $user = Auth::user();
        if ($request->ajax()) {
            if ($user->isAdmin()) {
                $tasks = Task::orderBy('id', 'DESC')->with(['category:id,name', 'sprint:id,title,phase_id', 'sprint.phase:id,title,project_id', 'sprint.phase.project:id,title', 'user:id,fname,lname'])
                    ->get();
            } else {
                $tasks = $user->tasks()->with(['category:id,name', 'sprint:id,title,phase_id', 'sprint.phase:id,title,project_id', 'sprint.phase.project:id,title', 'user:id,fname,lname'])
                    ->get();

                // $tasks = [];
                // foreach ($user->projects as $project) {
                //     foreach ($project->phases as $phase) {
                //         foreach($phase->sprints as $sprint){
                //             $tasks[]=$sprint->tasks()
                //             ->with(['category:id,name', 'sprint:id,title,phase_id',
                //             'sprint.phase:id,title,project_id', 'sprint.phase.project:id,title',
                //             'user:id,fname,lname'])->orderBy('created_at','DESC')->get();
                //         }
                //         $tasks = collect($tasks)->collapse();
                //     }
                // }
            }

            $recordTotal = $tasks->count();
            $data = array(
                "draw" => intval($request['draw']),
                "recordsTotal" => intval($recordTotal),
                "recordsFiltered" => intval(2),
                "data" => $tasks
            );

            return response()->json($data);
        }
        return view('task.owner');
    }


    public function taskBoard(Request $request)
    {

        $user = Auth::user();
        if ($request->ajax()) {
            $tasks = [];
            if ($user->isAdmin()) {

                    $tasks = Task::with('user:id,fname,lname,background_color,text_color', 'sprint:id,title,phase_id', 'sprint.phase:id,title,project_id', 'sprint.phase.project:id,title')->orderBy('updated_at', 'desc')->orderBy('status', 'asc')->where('todo_date', '!=', null)
                        ->whereHas('user', function (Builder $query) use ($request) {
                            $query->where('lname', 'like', '%' . $request->user . '%');
                        })->whereHas('sprint.phase.project', function (Builder $query) use ($request) {
                            $query->where('title', 'like', '%' . $request->project . '%');
                        })->get();
                // }
            } else {
                $tasks = [];

                    $projects =
                        $user->projects()->where('title', 'like', '%' . $request->project . '%')
                        ->get();

                foreach ($projects as $project) {
                    foreach ($project->phases as $phase) {
                        foreach ($phase->sprints as $sprint) {
                            foreach ($sprint->tasks()->with('user')->whereHas('user',function(Builder $query) use($request){
                                $query->where('lname','like','%'.$request->user.'%');
                            } )->get() as $task) {
                                if ($task->todo_date) {

                                    $tasks[] = $task;
                                }
                            }
                        }
                    }
                }
                // sort tasks
                $tasks = collect($tasks);
                $tasks = $tasks->sortBy([
                    ['updated_at', 'desc'],
                    ['status', 'asc']
                ]);
            }
            return response()->json($tasks);
        }
        return view('task.task_board');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request, $task_id = null)
    {
        $sprint = Sprint::find($request->sprint_id);
        if ($task_id) {
            $task = Task::findOrFail($task_id);
            $response = Gate::inspect('update', $task);
        } else {
            $response = Gate::inspect('create', [Task::class, $sprint->id]);
        }

        if (!$response->allowed()) {
            return response()->json(['errors' => ['message' => $response->message()]], 403);
        }
        $users = $sprint->phase->project->users;

        $user = $users->find(Auth::id());

        $inputs = $request->all();
        if ($user && $user->pivot->developer) {
            $inputs['user_id'] = Auth::id();
        }
        // if(!$user->isAdmin()){
        // $inputs['user_id'] = $request->user_id;
        // }
        $inputs['sprint_id'] = $request->sprint_id;
        $inputs['category_id'] = $request->category;
        if ($request->confirm || $sprint->task_confirm) {
            $inputs['todo_date'] = Carbon::now();
        }
        Task::updateOrCreate(['id' => $task_id], $inputs);
        if ($task_id) {
            return response()->json(['message' => 'تسک مورد نظر به روز شد.']);
        }
        return response()->json(['message' => 'تسک ایجاد شد.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {


        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function changeStatus(Task $task)
    {
        $user = Auth::user();
        if ($task->status == 0) {
            $user->tasks()->whereNull('done_date')->where('play', 1)->update(['play' => 0]);
            $task->status = 1;
            $task->play = 1;
            Time::create([
                'task_id' => $task->id,
                'start' => Carbon::now()
            ]);
            $task->indo_date = Carbon::now();
        } else if ($task->status == 1) {
            $task->status = 2;
            $task->done_date = Carbon::now();
            $task->play = 1;
        }
        $task->save();
        return response()->json($task);
    }



    public function playPause(Task $task)
    {
        $user = Auth::user();
        $time = $task->times()->latest()->first();
        if ($time->stop) {
            Time::create([
                'task_id' => $task->id,
                'start' => Carbon::now()
            ]);
            $user->tasks()->whereNull('done_date')->where('play', 1)->update(['play' => 0]);
            $task->play = 1;
        } else {
            $time->stop = Carbon::now();
            $task->play = 0;
            $time->save();
        }
        $task->save();

        // }
        return response()->json();
    }

    public function accept(Task $task)
    {
        if ($task->indo_date == null && $task->done_date == null && $task->todo_date != null) {
            $task->update(['todo_date' => null]);
            return response()->json(['message' => 'تسک مورد نظر لغو تایید  شد.']);
        } else {
            $task->update(['todo_date' => now()]);
            return response()->json(['message' => 'تسک مورد نظر تایید شد.']);
        }
    }

    public function addComment(Request $request, Task $task)
    {
        $task->comment = $request->comment;
        $task->save();
        return response()->json(['message' => 'توضیحات پایانی افزوده شد.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {

        $task->delete();
        return response()->json(['message' => 'تسک مورد نظر حذف شد.']);
    }
}
