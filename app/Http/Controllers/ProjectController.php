<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($request->ajax()) {
            if ($user->isAdmin()) {
                $projects = Project::select('id', 'project_id', 'title', 'description', 'start_date', 'end_date')->with('parent:id,title')->get();
            } else {
                $projects = $user->projects()->with('parent:id,title')->without('pivot')->get();
            }
            $recordTotal = $projects->count();
            $data = array(
                "draw" => intval($request['draw']),
                "recordsTotal" => intval($recordTotal),
                "recordsFiltered" => intval(2),
                "data" => $projects
            );
            return response()->json($data);
        }

        return view('project.index');
    }

    public function getProjects()
    {
        $projects = Project::select('id', 'title')->get();
        return response()->json($projects);
    }


    public function getProjectUsers(Project $project)
    {

        return response()->json($project->users);
    }

    public function getUsers(Request $request)
    {
        $users = User::select('id', 'fname', 'lname')->get();
        $recordTotal = $users->count();
        $data = array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($recordTotal),
            "recordsFiltered" => intval(2),
            "data" => $users
        );
        return response()->json($data);
    }


    public function addUsers(Request $request, Project $project)
    {

        $current_developers = $project->users()->wherePivot('developer', 1)->get();
        if ($request->has('developer')) {
            foreach ($current_developers as $current_developer) {
                if (!in_array($current_developer->id, $request->developer)) {
                    $project->users()->updateExistingPivot(
                        $current_developer->id,
                        ['developer' => 0]
                    );
                }
            }

            foreach ($request->developer as $developer) {

                $this->setPermission($project, $developer, 'developer');
            }
        }

        if ($request->admin) {

            $this->setPermission($project, $request->admin, 'admin');
        }
        if ($request->owner) {

            $this->setPermission($project, $request->owner, 'owner');
        }

        $project->users()->wherePivot('owner', 0)
            ->wherePivot('admin', 0)->wherePivot('developer', 0)->detach();

        return response()->json(['message' => 'کاربرهابه این پروژه افزوده شد.']);
    }


    private function setPermission(Project $project, $user_id, $role)
    {
        $project_user = $project->users()->find($user_id);
        if ($role == 'admin' || $role == 'owner') {

            $project->users()->wherePivot($role, 1)->update([$role => 0]);
        }
        if ($project_user) {
            $project->users()->updateExistingPivot($user_id, [$role => 1]);
        } else {
            $project->users()->attach(User::find($user_id), [$role => 1]);
        }
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
    public function store(ProjectRequest $request, $project_id = null)
    {
        $inputs = $request->all();
        $inputs['user_id'] = Auth::id();
        $inputs['start_date'] = convertJalaliToGeorgian($request->start_date);
        $inputs['end_date']   = convertJalaliToGeorgian($request->end_date);
        if ($request->project_id == 0) {
            $inputs['project_id'] = null;
        }
        Project::updateOrCreate(['id' => $project_id], $inputs);
        if ($project_id) {
            return response()->json(['message' => 'پروژه مورد نظر تغییر پیدا کرد.']);
        }
        return response()->json(['message' => 'پروژه با موفقیت ثبت شد.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return response()->json($project);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'پروژه مورد نظر حذف شد.']);
    }
}
