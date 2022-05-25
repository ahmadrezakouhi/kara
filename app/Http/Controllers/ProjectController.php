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
use App\Models\ProjectUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

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
            // 0 => 'id',
            0 => 'title',
            1 => 'StartDate',
            2 => 'EndDatePre',
            3 => 'level',
            4 => 'parentlevel',
        );
        $queryFiltered = DB::table('projects');

        // if( Auth::user()->role != "manager"){
        //     $projectUserFiltered = DB::table('project_users');
        //     $projectUserFiltered = $projectUserFiltered->select("project_id")->where('userid', Auth::user()->id)->where('title',0)->get();       
        //     $projectsid=[];
        //     for ($i=0; $i < count($projectUserFiltered); $i++) $projectsid[]= $projectUserFiltered[$i]->project_id;
        //     $queryFiltered = $queryFiltered->whereIn("id",$projectsid);
        // }
        $recordsTotal = $queryFiltered->count();
    //    var_dump( isset( $request['sf']['search-parent-id'])? true: false);
    //    exit();
        if (isset($request['sf'])){
            if ( 
                 (!isset( $request['sf']['search-parent-id']) || ( isset( $request['sf']['search-parent-id']) && ($request['sf']['search-parent-id'] == "0" || $request['sf']['search-parent-id'] == "-1" )))
                 ){
                    //  echo "Ddd";exit();
                if($request['sf']['search-title'] != '')
                    $queryFiltered =  $queryFiltered->where('title', 'like','%' .$request['sf']['search-title']. '%' );   
                if($request['sf']['search-start-date'] != ''){
                        $dateString = \Morilog\Jalali\CalendarUtils::convertNumbers($request['sf']['search-start-date'], true); 
                        $start_date    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateString)->format('Y-m-d H:i:s');
                        $queryFiltered =  $queryFiltered->where('start_date', '>=', $start_date); 
                }
                if($request['sf']['search-start-date-to'] != ''){
                    $todateString = \Morilog\Jalali\CalendarUtils::convertNumbers($request['sf']['search-start-date-to'], true); 
                    $start_date_to    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $todateString)->format('Y-m-d H:i:s');
                    $queryFiltered =  $queryFiltered->where('start_date', '<=', $start_date_to); 
                }
                if($request['sf']['search-end-date'] != ''){
                    $enddateString = \Morilog\Jalali\CalendarUtils::convertNumbers($request['sf']['search-end-date'], true); 
                    $end_date    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $enddateString)->format('Y-m-d H:i:s');
                    $queryFiltered =  $queryFiltered->where('end_date_pre', '>=', $end_date); 
                }
                
                if($request['sf']['search-end-date-to'] != ''){
                    $toenddateString = \Morilog\Jalali\CalendarUtils::convertNumbers($request['sf']['search-end-date-to'], true); 
                    $end_date_to    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $toenddateString)->format('Y-m-d H:i:s');
                    $queryFiltered =  $queryFiltered->where('end_date_pre', '<=', $end_date_to); 
                }
                $queryFiltered = $queryFiltered->whereIn('id',function($query){

                    $query->select('project_id')->from('project_users')->where("userid",Auth::user()->id );
     
                 });
                $recordsFiltered = $queryFiltered->count();
                $data = $queryFiltered->orderBy($columns[$request['order'][0]['column']],$request['order'][0]['dir'])->offset($request['start'])->limit($request['length'])->get();
        
            }else{
                $where=" 1 ";
                if( $request['sf']['search-title'] != '' )
                    $where .=  " AND s.title like '%" . $request['sf']['search-title'] . "%'";
                if($request['sf']['search-start-date'] != ''){
                    $dateString = \Morilog\Jalali\CalendarUtils::convertNumbers($request['sf']['search-start-date'], true); 
                    $start_date    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateString)->format('Y-m-d H:i:s');
                    $where .=  " AND s.start_date >='" . $start_date . "'";
                }
                if($request['sf']['search-start-date-to'] != ''){
                    $dateStringto = \Morilog\Jalali\CalendarUtils::convertNumbers($request['sf']['search-start-date-to'], true); 
                    $start_date_to    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $dateStringto)->format('Y-m-d H:i:s');
                    $where .=  " AND s.start_date <='" . $start_date_to . "'";
                }

                if($request['sf']['search-end-date'] != ''){
                    $enddateString = \Morilog\Jalali\CalendarUtils::convertNumbers($request['sf']['search-end-date'], true); 
                    $end_date_to    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $enddateString)->format('Y-m-d H:i:s');
                    $where .=  " AND s.end_date_pre >='" . $end_date_to . "'";
                }
              
                if($request['sf']['search-end-date-to'] != ''){
                    $enddateStringto = \Morilog\Jalali\CalendarUtils::convertNumbers($request['sf']['search-end-date-to'], true); 
                    $end_date_to    =   \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $enddateStringto)->format('Y-m-d H:i:s');
                    $where .=  " AND s.end_date_pre <='" . $end_date_to . "'";
                }   

                $where .= " AND s.id IN (SELECT project_id from project_users where userid = '" . Auth::user()->id ."')";
                $manual_query = "SELECT * FROM (SELECT id, parent_level_id, title, description, start_date, end_date_pre, user_id, parent_level_name, progress, '0' as depth, @tree_ids := id AS foo FROM projects, (SELECT @tree_ids := '', @depth := -1) vars WHERE id = '" . $request['sf']['search-parent-id'] . "' UNION SELECT id, parent_level_id, title, description, start_date, end_date_pre, user_id, parent_level_name, progress, @depth := IF(parent_level_id = '" . $request['sf']['search-parent-id'] . "', 1, @depth + 1) AS depth, @tree_ids := CONCAT(id, ',', @tree_ids) AS foo FROM projects WHERE FIND_IN_SET(parent_level_id, @tree_ids) OR parent_level_id ='" . $request['sf']['search-parent-id'] . "') s where ". $where ." ";

                $data= DB::select($manual_query);
                $recordsFiltered = count($data);
            }
        }   else{
            $queryFiltered = $queryFiltered->whereIn('id',function($query){

                $query->select('project_id')->from('project_users')->where("userid",Auth::user()->id );
 
             });
            $recordsFiltered = $queryFiltered->count();
            $data = $queryFiltered->orderBy($columns[$request['order'][0]['column']],$request['order'][0]['dir'])->offset($request['start'])->limit($request['length'])->get();
    
        } 
            

        
      
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

            if ( Gate::allows('isAdmin')) { 
                $parent_project = project::find($request->project_id);
                $project->parent_level_id  = $request->project_id;
                $project->parent_level_name  = $parent_project->title;
            }
            if ( Gate::allows('isManager')) { 
                $project->parent_level_id  = $request->project_id;
                if($request->project_id !=0){
                    $parent_project = project::find($request->project_id);
                    $project->parent_level_name  = $parent_project->title;
                }
            }
            $personnel_users = new ProjectUser();
            $data_user = DB::table('users')->find(Auth::user()->id);
            $personnel_users->userid       = Auth::user()->id; 
            $personnel_users->fname       = $data_user->fname; 
            $personnel_users->lname       = $data_user->lname;
            $personnel_users->project_id  = $request->title;
            $personnel_users->title       = 0;  //admin
            $personnel_users->status      = 1;
            $personnel_users->user_id     = Auth::user()->id;
            $personnel_users->save();
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
            if ( Gate::allows('isAdmin')) { 
                $parent_project = project::find($request->project_id);
                $project->parent_level_id  = $request->project_id;
                $project->parent_level_name  = $parent_project->title;
            }
            if ( Gate::allows('isManager')) { 
                $project->parent_level_id  = $request->project_id;
                if($request->project_id !=0){
                    $parent_project = project::find($request->project_id);
                    $project->parent_level_name  = $parent_project->title;
                }
            }
            $project->user_id     = Auth::user()->id;
            $project->save();

            $personnel_users = new ProjectUser();
            $data_user = DB::table('users')->find(Auth::user()->id);
            $personnel_users->userid       = Auth::user()->id; 
            $personnel_users->fname       = $data_user->fname; 
            $personnel_users->lname       = $data_user->lname;
            $personnel_users->project_id  = $project->id;
            $personnel_users->title       = 0;  //admin
            $personnel_users->status      = 1;
            $personnel_users->user_id     = Auth::user()->id;
            // echo '<pre>';
            // var_dump($personnel_users);exit();
            $personnel_users->save();
            
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


    public function getProjects(Request $request){
        $queryFiltered = DB::table('projects');
        if($request->project_id  != 0)
            $queryFiltered->where('id', '!=', $request->project_id);
        if (Gate::allows('isAdmin')  )
        {
            $projectuser_query= DB::table('project_users')->where('userid', Auth::user()->id)->where("title",0)->get();
            $project = [];
            for ($i=0; $i< count($projectuser_query); $i++) $project[] = $projectuser_query[$i]->project_id;
            $queryFiltered->whereIn('id',$project);
        }
        $json_data =  $queryFiltered->orderBy('title', 'asc')->get();
        return response()->json($json_data);
    }

    public function getParentProjects(Request $request){
        $queryFiltered = DB::table('projects');
       
        if (Gate::allows('isAdmin')  )
        {
            $projectuser_query= DB::table('project_users')->where('userid', Auth::user()->id)->where("title",0)->get();
            $project = [];
            for ($i=0; $i< count($projectuser_query); $i++) $project[] = $projectuser_query[$i]->project_id;
            
            $queryFiltered->whereIn('id',$project);
        }
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
    public function destroy(Request $request, Project $project)
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
