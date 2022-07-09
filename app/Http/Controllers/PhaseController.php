<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhaseRequest;
use App\Models\Phase;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PhaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        if ($request->ajax()) {
            $phases = $project->phases;
            $recordTotal = $phases->count();
            $data = array(
                "draw" => intval($request['draw']),
                "recordsTotal" => intval($recordTotal),
                "recordsFiltered" => intval(2),
                "data" => $phases
            );
            return response()->json($data);
        }
        return view('phase.index', compact('project'));
    }

    public function owner(Request $request){
        $user = Auth::user();
        if($request->ajax()){
            if($user->isAdmin()){
                $phases = Phase::select('id','title','description','project_id','start_date','end_date')
                ->with(['project:id,title','project.users'=>function($query){
                    return $query->where('admin','1')->select('fname','lname');
                }])
                ->get();
            }else{
                $phases = [];
                foreach($user->projects as $project){
                    $phases[]=$project->phases()->with(['project:id,title','project.users'=>function($query){
                        return $query->where('admin','1')->select('fname','lname');
                    }])->get();
                }
                $phases = collect($phases)->collapse();
            }


            $recordTotal = $phases->count();
            $data = array(
                "draw" => intval($request['draw']),
                "recordsTotal" => intval($recordTotal),
                "recordsFiltered" => intval(2),
                "data" => $phases
            );
            return response()->json($data);
        }
        return view('phase.owner');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PhaseRequest $request, $phase_id = null)
    {
        if ($phase_id) {
            $phase = Phase::findOrFail($phase_id);

            $response = Gate::inspect('update', $phase);
        } else {
            $response = Gate::inspect('create', [Phase::class,$request->project_id]);
        }

        if (!$response->allowed()) {
            return response()->json(['errors' => ['message' => $response->message()]], 403);
        }

        $user = Auth::user();
        $project = Project::findOrFail($request->project_id);
        $inputs = $request->all();
        $inputs['user_id'] = $user->id;
        $inputs['start_date'] = convertJalaliToGeorgian($request->start_date);
        $inputs['end_date']   = convertJalaliToGeorgian($request->end_date);
        $inputs['priority'] = ($project->phases->count() + 1);
        Phase::updateOrCreate(['id' => $phase_id], $inputs);
        if ($phase_id) {
            return response()->json(['message' => 'فاز مورد نظر تغییر پیدا کرد.']);
        }
        return response()->json(['message' => 'فاز جدید افزوده شد.']);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Phase $phase)
    {
        return response()->json($phase);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Phase $phase)
    {
        $phase->delete();
        return response()->json(['message' => 'فاز مورد نظر حذف شد.']);
    }
}
