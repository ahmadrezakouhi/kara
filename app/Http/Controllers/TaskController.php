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
        return view('task.index', compact('sprint', 'categories'));
    }



    public function owner(Request $request)
    {
        $user = Auth::user();
        if ($request->ajax()) {
            if ($user->isAdmin()) {
                $tasks = Task::with(['category:id,name', 'sprint:id,title,phase_id', 'sprint.phase:id,title,project_id', 'sprint.phase.project:id,title', 'user:id,fname,lname'])
                    ->get();
            } else {
                // $tasks = $user->tasks()->with(['category:id,name', 'sprint:id,title,phase_id', 'sprint.phase:id,title,project_id', 'sprint.phase.project:id,title', 'user:id,fname,lname'])
                //     ->get();

                $tasks = [];
                foreach ($user->projects as $project) {
                    foreach ($project->phases as $phase) {
                        foreach($phase->sprints as $sprint){
                            $tasks[]=$sprint->tasks()
                            ->with(['category:id,name', 'sprint:id,title,phase_id',
                            'sprint.phase:id,title,project_id', 'sprint.phase.project:id,title',
                            'user:id,fname,lname'])->get();
                        }
                        $tasks = collect($tasks)->collapse();
                    }
                }
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
                $tasks = Task::where('todo_date','!=',null)->get();
            } else {
                $tasks=[];
                foreach ($user->projects as $project) {
                    foreach ($project->phases as $phase) {
                        foreach ($phase->sprints as $sprint) {
                            foreach ($sprint->tasks as $task) {
                                if ($task->todo_date) {
                                    $tasks[] = $task;
                                }
                            }
                        }
                    }
                }


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

        $user = Auth::user();
        $inputs = $request->all();
        $inputs['user_id'] = $user->id;
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
            $user->tasks()->where('play',1)->update(['play'=>0]);
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
            $user->tasks()->where('play',1)->update(['play'=>0]);
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
