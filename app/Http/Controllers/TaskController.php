<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return \View::make('task.index')
        ->with('task');
    }

    public function getData(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'StartDate',
            3 => 'EndDatePre',
            4 => 'project_title',
        );
        $queryFiltered = DB::table('tasks');
        $projectUserFiltered = DB::table('project_users');

        if( Auth::user()->role == "admin"){

            $projectUserFiltered = $projectUserFiltered->select("userid", "project_id", "title")->
            where(function($query) {
                $query->where('userid', Auth::user()->id)
                      ->where('title',0);
            })->orWhere('userid', Auth::user()->id)->get();  
            // echo '<pre>';
            // var_dump($projectUserFiltered);exit();
            $projectsid=[];
            $projectsiduser=[];
            for ($i=0; $i < count($projectUserFiltered); $i++) {
                if($projectUserFiltered[$i]->title == 0)
                    $projectsid[]= $projectUserFiltered[$i]->project_id;
                else
                    $projectsiduser[]= $projectUserFiltered[$i]->project_id;
            }

            
            $queryFiltered = $queryFiltered->whereIn("project_id",$projectsid);
            $queryFiltered = $queryFiltered->orWhere(function($query) use ($projectsiduser){
                $query->whereIn("project_id",$projectsiduser)
                       ->where('userid', Auth::user()->id);
            });
        }

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
        return \View::make('task.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        //
         //

         $rules = array(
            // 'title'           => 'required',
            // 'start_date'      => 'required',
            // 'end_date_pre'    => 'required',
        );
        
        $validator = Validator::make($request->all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect('task/create')
                        ->withErrors($validator)
                        ->withInput();
        } else {
            // store
            $task = new Task;
            $task->title         = $request->title; 
            $task->description   = $request->description;
           
            $dateString = \Morilog\Jalali\CalendarUtils::convertNumbers($request->start_date, true); 
            $task->start_date    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateString)->format('Y-m-d H:i:s');
            
            $dateStringEnd_date_pre = \Morilog\Jalali\CalendarUtils::convertNumbers($request->end_date_pre, true); 
            $task->end_date_pre     =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateStringEnd_date_pre)->format('Y-m-d H:i:s');
            // $project->description = $request->description;
            $project = DB::table('projects')->find($request->project_id);
            $task->project_id    = $request->project_id;
            $task->project_title = $project->title;
            $task->user_id       = Auth::user()->id;
            $task->userid       = $request->userid;
            $user = DB::table('project_users')->find($request->userid);
            $task->username       = $user->fname . " " . $user->lname;
            $task->save();

            // redirect
            $request->session()->flash('message', 'task ' . $request->title .' با موفقیت ثبت شد!');
            return redirect('task');
        }
    }

    public function addTask(Request $request)
    {
            //
            if (Gate::allows('isManager') ||  Gate::allows('isAdmin')  || $request->user()->can('update',$project)) {
                    // store
                    $project = DB::table('projects')->find($request->project_id);
                    $task = new Task;
                    $task->project_id = $request->project_id;
                    $task->project_title = $project->title;
                    $dateString = \Morilog\Jalali\CalendarUtils::convertNumbers($request->start_date, true); 
                    $task->start_date    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateString)->format('Y-m-d H:i:s');
            
                    $dateStringEnd_date_pre = \Morilog\Jalali\CalendarUtils::convertNumbers($request->end_date, true); 
                    $task->end_date_pre     =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateStringEnd_date_pre)->format('Y-m-d H:i:s');
                 
                    $task->title  = $request->title;
                    $task->userid  = $request->userid;
                    $task->username  = $request->username;
                    $task->description  = $request->description;
                    $task->user_id     = Auth::user()->id;
                    $task->save();
    
                    // redirect
                    $request->session()->flash('message', 'فعالیت ' . $request->title . ' برای کاربر ' .  $request->username .'ثبت گردید !');
                    return redirect('project');
            }else{
                $request->session()->flash('message', 'عدم دسترسی!');
                return redirect('project');
            }
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
        $task = Task::find($task->id);        
        
        // show the view and pass the task to it
        return \View::make('task.show')
            ->with('task', $task);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
        $queryFiltered = DB::table('projects');
        $projects =  $queryFiltered->orderBy('title', 'asc')->get();
        
       

        $task = Task::find($task->id);

        $usersFiltered = DB::table('project_users');
        $users =  $usersFiltered->where("project_id", $task->project_id)->get();

        // show the edit form and pass the task
        return \View::make('task.edit')
            ->with('task', $task)->with('projects', $projects)->with('users', $users);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        //
        if (Gate::allows('isManager') ||  Gate::allows('isAdmin')  || $request->user()->can('update',$task)) {
            $rules = array(
                'title'       => 'required',
                'start_date'   => 'required',
                'end_date_pre'   => 'required',
            );
            $validator = Validator::make($request->all(), $rules);

            // process the login
            if ($validator->fails()) {
                return redirect('task/' . $task->id . '/edit')
                    ->withErrors($validator)
                    ->withInput();
            } else {
                // store
                $task = Task::find($task->id);
                $task->title         = $task->title; 
                $task->description   = $task->description;
                $dateString = \Morilog\Jalali\CalendarUtils::convertNumbers($request->start_date, true); 
                $task->start_date    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateString)->format('Y-m-d H:i:s');
            
                $dateStringEnd_date_pre = \Morilog\Jalali\CalendarUtils::convertNumbers($request->end_date_pre, true); 
                $task->end_date_pre    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateStringEnd_date_pre)->format('Y-m-d H:i:s');
                // $project->description = $request->description;
                // $project->level         = $request->level;
                $project = DB::table('projects')->find($request->project_id);
                $task->project_id    = $request->project_id;
                $task->project_title = $project->title;
                $task->userid       = $request->userid; 
                $user = DB::table('project_users')->find($request->userid);
                $task->username       = $user->fname . " " . $user->lname;
                $task->user_id     = Auth::user()->id;
                $task->save();

                // redirect
                $request->session()->flash('message', 'task با موفقیت بروز رسانی شد!');
                return redirect('task');
            }
        }else{
            $request->session()->flash('message', 'عدم دسترسی!');
            return redirect('project');
        }
    }

    public function setDoneTask(Request $request)
    {
        # code...
        if (Gate::allows('isManager') ||  Gate::allows('isAdmin')  || $request->user()->can('update',$task)) {
            $task = Task::find($request->id);
            date_default_timezone_set("Asia/Tehran");
            $task->time_do    =  $request->type == 1 ? date('Y-m-d H:i:s') : null;
            $task->status = $request->type;
            $task->save();

                // redirect
            $request->session()->flash('message', 'وضعیت فعالیت تغییر کرد.');
            return $request->type;
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Task $task)
    {
        //
        if (Gate::allows('isManager') ||  Gate::allows('isAdmin')  || $request->user()->can('delete',$task)) {
            //
                // delete
                $task = Task::find($task->id);
                $task->delete();
    
                // redirect
                $request->session()->flash('message', 'Task با موفقیت حذف شد!');
                return true;
            }else{
                $request->session()->flash('message', 'عدم دسترسی برای حذف!');
                abort(403);
            }
    }
}
