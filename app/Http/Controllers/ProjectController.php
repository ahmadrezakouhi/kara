<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return View::make('project.index')
        ->with('project' );
    }

    public function getData(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'StartDate',
            3 => 'EndDatePre',
            4 => 'level',
            5 => 'parentlevel',
        );
        $queryFiltered = DB::table('projects');

        if( Auth::user()->role != "manager"){
            $projectUserFiltered = DB::table('project_users');
            $projectUserFiltered = $projectUserFiltered->select("project_id")->where('userid', Auth::user()->id)->where('title',0)->get();       
            $projectsid=[];
            for ($i=0; $i < count($projectUserFiltered); $i++) $projectsid[]= $projectUserFiltered[$i]->project_id;
            $queryFiltered = $queryFiltered->whereIn("id",$projectsid);
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
        return \View::make('project.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        //

        $rules = array(
            // 'title'           => 'required',
            // 'start_date'      => 'required',
            // 'end_date_pre'    => 'required',
        );
        
        $validator = Validator::make($request->all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect('project/create')
                        ->withErrors($validator)
                        ->withInput();
        } else {
            // store
            $project = new Project;
            $project->title         = $request->title; 
            $project->description   = $request->description;
           
            $dateString = \Morilog\Jalali\CalendarUtils::convertNumbers($request->start_date, true); 
            $project->start_date    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateString)->format('Y-m-d H:i:s');
            
            $dateStringEnd_date_pre = \Morilog\Jalali\CalendarUtils::convertNumbers($request->end_date_pre, true); 
            $project->end_date_pre    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateStringEnd_date_pre)->format('Y-m-d H:i:s');
            // $project->description = $request->description;
            // $project->level         = $request->level;
            // $project->parent_level  = $request->parent_level;
            $project->user_id     = Auth::user()->id;
            $project->save();

            // redirect
            $request->session()->flash('message', 'پروژه با موفقیت ثبت شد!');
            return redirect('project');
        }
    }

    public function addProject(StoreProjectRequest $request)
    {
        //

        $rules = array(
            // 'title'           => 'required',
            // 'start_date'      => 'required',
            // 'end_date_pre'    => 'required',
        );
        
        $validator = Validator::make($request->all(), $rules);
        // process the login
        if ($validator->fails()) {
            return -1;
        } else {
            // store
            
            $project = new Project;
            $project->title         = $request->title; 
            $project->description   = $request->description;
           
            $dateString = \Morilog\Jalali\CalendarUtils::convertNumbers($request->start_date, true); 
            $project->start_date    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateString)->format('Y-m-d H:i:s');
            
            $dateStringEnd_date_pre = \Morilog\Jalali\CalendarUtils::convertNumbers($request->end_date_pre, true); 
            $project->end_date_pre    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateStringEnd_date_pre)->format('Y-m-d H:i:s');
            // $project->description = $request->description;
            // $project->level         = $request->level;
            // $project->parent_level  = $request->parent_level;
            $project->user_id     = Auth::user()->id;
            $project->save();

            // redirect
            $request->session()->flash('message', 'پروژه با موفقیت ثبت شد!');
            return response()->json($project);
        }
    }

    public function addParent(Request $request)
    {
            //
            if (Gate::allows('isManager') ||  Gate::allows('isAdmin')  || $request->user()->can('update',$project)) {
                    // store
                    $project = project::find($request->project_id);
                    $project->parent_level_id  = $request->parent_level_id;
                    $project->parent_level_name  = $request->parent_level_name;
                    $project->user_id     = Auth::user()->id;
                    $project->save();
    
                    // redirect
                    $request->session()->flash('message', 'مدیر پروژه با موفقیت بروز رسانی شد!');
                    return redirect('project');
            }else{
                $request->session()->flash('message', 'عدم دسترسی!');
                return redirect('project');
            }
    
    }


    public function getProjects(){
        $queryFiltered = DB::table('projects');
        $json_data =  $queryFiltered->orderBy('title', 'asc')->get();
        return response()->json($json_data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\project  $project
     * @return \Illuminate\Http\Response
     */
    
    public function show($id)
    {
        //
        $project = Project::find($id);
        
        $queryFiltered = DB::table('project_users');
        $users =  $queryFiltered->where('project_id', $id)->orderBy('title', 'asc')->get();
        
        $querySubPoject = DB::table('projects');
        $subPoject =  $querySubPoject->where('parent_level_id', $id)->orderBy('parent_level_name', 'asc')->get();

        $queryTaskPoject = DB::table('tasks');
        $taskPoject =  $queryTaskPoject->where('project_id', $id)->orderBy('id', 'asc')->get();
        // show the view and pass the project to it
        return \View::make('project.show')
            ->with('project', $project)->with('users', $users)->with('sub_projects', $subPoject)->with('tasks', $taskPoject);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
         // get the project
         $project = Project::find($id);

         // show the edit form and pass the project
         return \View::make('project.edit')
             ->with('project', $project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, project $project)
    {
        //
        if (Gate::allows('isManager') ||  Gate::allows('isAdmin')  || $request->user()->can('update',$project)) {
            $rules = array(
                'title'       => 'required',
                'start_date'   => 'required',
                'end_date_pre'   => 'required',
            );
            $validator = Validator::make($request->all(), $rules);

            // process the login
            if ($validator->fails()) {
                return redirect('project/' . $project->id . '/edit')
                    ->withErrors($validator)
                    ->withInput();
            } else {
                // store
                $project = project::find($project->id);
                $project->title         = $request->title; 
                $project->description   = $request->description;
                $dateString = \Morilog\Jalali\CalendarUtils::convertNumbers($request->start_date, true); 
                $project->start_date    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateString)->format('Y-m-d H:i:s');
            
                $dateStringEnd_date_pre = \Morilog\Jalali\CalendarUtils::convertNumbers($request->end_date_pre, true); 
                $project->end_date_pre    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateStringEnd_date_pre)->format('Y-m-d H:i:s');
                // $project->description = $request->description;
                // $project->level         = $request->level;
                // $project->parent_level  = $request->parent_level;
                $project->user_id     = Auth::user()->id;
                $project->save();

                // redirect
                $request->session()->flash('message', 'پروژه با موفقیت بروز رسانی شد!');
                return redirect('project');
            }
        }else{
            $request->session()->flash('message', 'عدم دسترسی!');
            return redirect('project');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Personnel $project)
    {
        //
        if (Gate::allows('isManager') ||  Gate::allows('isAdmin')  || $request->user()->can('delete',$project)) {
            //
                // delete
                $project = Project::find($project->id);
                $project->delete();
    
                // redirect
                $request->session()->flash('message', 'پروژه با موفقیت حذف شد!');
                return true;
            }else{
                $request->session()->flash('message', 'عدم دسترسی برای حذف!');
                abort(403);
            }
    }
}
