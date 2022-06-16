<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Sprint;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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


    public function taskBoard(Request $request)
    {
        if ($request->ajax()) {
            $tasks = Task::all();
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
        $user = Auth::user();
        $inputs = $request->all();
        $inputs['user_id'] = $user->id;
        $inputs['sprint_id'] = $request->sprint_id;
        $inputs['category_id'] = $request->category;
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
    public function edit($id)
    {
        $task = Task::findOrFail($id);
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


    public function changeStatus($task_id)
    {
        $task = Task::findOrFail($task_id);

        // if ($task->status >= 0 && $task->status <2) {

            if($task->status == 0){
                $task->status = 1;
                $task->indo_date = Carbon::now();
            }else if($task->status == 1) {
                $task->status =2;
                $task->done_date = Carbon::now();
            }
            $task->save();
            return response()->json($task);
            // return response()->json(['message' => 'وضعیت تغییر کرد.']);
        // }

        // return response()->json(['message' => 'امکان تغییر وضعیت وجود ندارد.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::destroy($id);
        return response()->json(['message' => 'تسک مورد نظر حذف شد.']);
    }
}
