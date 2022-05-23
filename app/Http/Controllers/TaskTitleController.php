<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskTitleRequest;
use App\Http\Requests\UpdateTaskTitleRequest;

use App\Http\Controllers\Controller;

use App\Models\TaskTitle;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class TaskTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return View::make('tasktitle.index')
        ->with('tasktitle');

    }
    public function getTaskTitles(Request $request){
        $queryFiltered = DB::table('task_titles');
        $json_data =  $queryFiltered->orderBy('title', 'asc')->get();
        return response()->json($json_data);
    }

    public function getData(Request $request){
        $columns = array(
            0 => 'title',
        );
        $queryFiltered = DB::table('task_titles');
           
        $recordsTotal = $queryFiltered->count();
        if (isset($request['sf'])){
            if($request['sf']['search-title'] != '')
                $queryFiltered =  $queryFiltered->where('title', $request['sf']['search-title']);            
        }
        $recordsFiltered = $queryFiltered->count();
        $data = $queryFiltered->get();

        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $data
        );
        return response()->json($json_data);
        // echo json_encode($json_data);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return View::make('tasktitle.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaskTitleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskTitleRequest $request)
    {
        //
        $validator = Validator::make($request->all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect('tasktitle/create')
                        ->withErrors($validator)
                        ->withInput();
        } else {
            // store

            $taskTitle = new TaskTitle();
            $taskTitle->title         = $request->title; 
            $taskTitle->user_id       = Auth::user()->id;
            $taskTitle->save();


            // redirect
            $request->session()->flash('message', 'عنوان فعالیت ' . $request->title .' با موفقیت ثبت شد!');
            return redirect('tasktitle');
        }
    }

    public function addTaskTitle(Request $request, TaskTitle $taskTitle)
    {
            //
            if (Gate::allows('isManager') ) {
                    // store
                    $taskTitle->title  = $request->title;
                    $taskTitle->user_id     = Auth::user()->id;
                    $taskTitle->save();
    
                    // redirect
                    $request->session()->flash('message', 'عنوان فعالیت ' . $request->title . 'ثبت گردید !');
                    return redirect('project');
            }else{
                $request->session()->flash('message', 'عدم دسترسی!');
                return redirect('tasktitle');
            }
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskTitle  $taskTitle
     * @return \Illuminate\Http\Response
     */
    public function show(TaskTitle $taskTitle)
    {
        //
        $taskTitle = TaskTitle::find($taskTitle->id);        
        
        // show the view and pass the task to it
        return View::make('tasktitle.show')
            ->with('tasktitle', $taskTitle);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskTitle  $taskTitle
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskTitle $taskTitle)
    {
        //

        $taskTitle = TaskTitle::find($taskTitle->id);

        // show the edit form and pass the task
        return View::make('tasktitle.edit')
            ->with('tasktitle', $taskTitle);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskTitleRequest  $request
     * @param  \App\Models\TaskTitle  $taskTitle
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskTitleRequest $request, TaskTitle $taskTitle)
    {
        //
        if (Gate::allows('isManager')) {
            $rules = array(
                'title'       => 'required',
            );
            $validator = Validator::make($request->all(), $rules);

            // process the login
            if ($validator->fails()) {
                return redirect('tasktitle/' . $taskTitle->id . '/edit')
                    ->withErrors($validator)
                    ->withInput();
            } else {
                // store
                $taskTitle = TaskTitle::find($taskTitle->id);
                $taskTitle->title         = $request->title; 
                $taskTitle->user_id     = Auth::user()->id;
                $taskTitle->save();

                // redirect
                $request->session()->flash('message', 'عنوان فعالیت با موفقیت بروز رسانی شد!');
                return redirect('taskTitle');
            }
        }else{
            $request->session()->flash('message', 'عدم دسترسی!');
            return redirect('taskTitle');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskTitle  $taskTitle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TaskTitle $taskTitle)
    {
        //
        if (Gate::allows('isManager') ) {
            //
            // delete
            $taskTitle = TaskTitle::find($taskTitle->id);
            $taskTitle->delete();

            // redirect
            $request->session()->flash('message', 'عنوان فعالیت با موفقیت حذف شد!');
            return true;
        }else{
            $request->session()->flash('message', 'عدم دسترسی برای حذف!');
            abort(403);
        }
    }
}
