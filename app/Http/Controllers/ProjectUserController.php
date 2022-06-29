<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectUserRequest;
use App\Http\Requests\UpdateProjectUserRequest;
use App\Models\ProjectUser;
use App\Models\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ProjectUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return View::make('project-user.index')
        ->with('project-user' );
    }

    public function getAllWithID($id){

        $queryFiltered = DB::table('project_users');
        if (isset($id)){
            $queryFiltered =  $queryFiltered->where('project_id', $id);
            $data = $queryFiltered->get();
            return response()->json($data);
        }else
            return false;
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
        return View::make('project-user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectUserRequest $request)
    {
        //
        $rules = array(
            'fname'       => 'required',
            'lname'       => 'required',
            'project_id'  => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return redirect('personnel-user/create')
                        ->withErrors($validator)
                        ->withInput();
        } else {
            // store
            $personnel_users = new PersonnelUser;
            $personnel_users->fname       = $request->fname;
            $personnel_users->lname       = $request->lname;
            $personnel_users->project_id  = $request->project_id;
            $personnel_users->title       = $request->title;
            $personnel_users->status      = $request->status;
            $personnel_users->user_id     = Auth::user()->id;
            $personnel_users->save();

            // redirect
            $request->session()->flash('message', 'کاربر پروژه '. $request->title . ' با موفقیت ثبت شد!');
            return redirect('personnelUser');
        }
    }

    public function add(StoreProjectUserRequest $request)
    {
        //

            // store

            $user_ids= explode(",",$request->users );
            $project_user = [];
            foreach($user_ids as $user_id){
                $project_user[] = ['user_id'=>$user_id,'project_id'=>$request->project_id,'status'=>2];
            }

            if($request->manage){
                $project_user[] = ['user_id'=>$request->manage,'project_id'=>$request->project_id,'status'=>1];
            }

            if($request->owner){
                $project_user[] = ['user_id'=>$request->owner,'project_id'=>$request->project_id,'status'=>0];

            }
            foreach($project_user as $record){
                $project = ProjectUser::where('user_id',$record['user_id'])
                ->where('project_id',$record['project_id'])->where('status',$record['status'])->get();
                if($project->count()==0){
                    ProjectUser::create($record);
                }


            }


            // $user = new User;
            // $queryFilteredTask = DB::table('tasks');

            // $projectuser_query= DB::table('project_users')->where('project_id', $request->project_id);
            // $projectuser_data= $projectuser_query->get();
            // // echo "<pre>";
            // // var_dump($projectuser_data);exit();
            // $oldUser = [];
            // for ($i=0; $i< count($projectuser_data); $i++) $oldUser[] = $projectuser_data[$i]->id;
            // // $oldUser[] = (int)$request->manage;

            // $projectuser_query->delete();
            // // echo "<pre>";
            // // var_dump($oldUser);exit();
            // for($i=0; $i< count($users); $i++){
            //     if($users[$i]!= $request->manage){
            //         $data = DB::table('users')->find($users[$i]);
            //         $personnel_users = new ProjectUser;
            //         $personnel_users->userid       = $users[$i];
            //         $personnel_users->fname       = $data->fname;
            //         $personnel_users->lname       = $data->lname;
            //         $personnel_users->project_id  = $request->project_id;
            //         $project = Project::find($request->project_id);
            //         $personnel_users->project_title  = $project->title;

            //         $personnel_users->status       = 1;  //member
            //         $personnel_users->user_id     = Auth::user()->id;
            //         $personnel_users->save();

            //         DB::table('tasks')->where('project_id', $request->project_id)->whereIn('userid', $oldUser)->where("username", "'".$data->fname . ' ' . $data->lname. "'")->update(['userid' => $personnel_users->id]);
            //     }
            // }
            // $personnel_users = new ProjectUser;
            // $data = DB::table('users')->find($request->manage);
            // $personnel_users->userid      = $request->manage;
            // $personnel_users->fname       = $data->fname;
            // $personnel_users->lname       = $data->lname;
            // $personnel_users->project_id  = $request->project_id;
            // $project = Project::find($request->project_id);
            // $personnel_users->project_title  = $project->title;

            // $personnel_users->status       = 0;  //admin
            // $personnel_users->user_id     = Auth::user()->id;
            // $personnel_users->save();
            // DB::table('tasks')->where('project_id', $request->project_id)->whereIn('userid', $oldUser)->where("username", $data->fname . ' ' . $data->lname)->update(['userid' => $personnel_users->id]);

            // redirect
            $request->session()->flash('message', 'کاربران پروژه با موفقیت ثبت شدند!');
            return response()->json(true);
    }

    public function get(ProjectUser $projectUser)
    {
        //
        $personnel_users = ProjectUser::find($projectUser->id);

        // show the view and pass the project to it
        return View::make('personnel-user.show')
            ->with('personnel-user', $personnel_users);
    }

    public function getUserByParentProject(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'fname',
            // 4 => 'email',
            // 5 => 'status',
        );

        $recordsTotal = DB::table('project_users')->count();
        $queryFiltered = DB::table('project_users');

        if (isset($request->_parent)){
            $queryFiltered =  $queryFiltered->where('project_id', $request->_parent);

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
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectUser  $projectUser
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectUser $projectUser)
    {
        //
        $personnel_users = ProjectUser::find($projectUser->id);

        // show the view and pass the project to it
        return View::make('personnel-user.show')
            ->with('personnel-user', $personnel_users);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectUser  $projectUser
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ProjectUser $projectUser)
    {
        //
        $projectUser = ProjectUser::find($request->id);

        // show the edit form and pass the project
        return View::make('personnel-user.edit')
            ->with('personnel-user', $projectUser);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectUserRequest  $request
     * @param  \App\Models\ProjectUser  $projectUser
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectUserRequest $request, ProjectUser $projectUser)
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
                return redirect('projectUser/' . $projectUser->id . '/edit')
                    ->withErrors($validator)
                    ->withInput();
            } else {
                // store
                $personnel_users = project::find($projectUser->id);
                $personnel_users->fname       = $request->fname;
                $personnel_users->lname       = $request->lname;
                $personnel_users->project_id  = $request->project_id;
                $personnel_users->title       = $request->title;
                $personnel_users->status      = $request->status;
                $personnel_users->user_id     = Auth::user()->id;
                $personnel_users->save();

                // redirect
                $request->session()->flash('message', 'کاربر پروژه با موفقیت بروز رسانی شد!');
                return redirect('projectUser');
            }
        }else{
            $request->session()->flash('message', 'عدم دسترسی!');
            return redirect('projectUser');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectUser  $projectUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectUser $projectUser)
    {
        //
        if (Gate::allows('isManager') ||  Gate::allows('isAdmin')  || $request->user()->can('delete',$project)) {
            //
            // delete
            $projectUser = Project::find($projectUser->id);
            $projectUser->delete();

            // redirect
            $request->session()->flash('message', 'کاربر پروژه با موفقیت حذف شد!');
            return true;
        }else{
            $request->session()->flash('message', 'عدم دسترسی برای حذف!');
            abort(403);
        }
    }


    public function getUserProjects(Request $request){
        $queryFiltered = DB::table('project_users');
        $json_data =  $queryFiltered->where("project_id", $request->project_id)->get();
        return response()->json($json_data);
    }
}
