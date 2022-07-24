<?php

namespace App\Http\Controllers;

use App\Http\Requests\SprintRequest;
use App\Models\Sprint;
use App\Models\Phase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SprintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $phase_id)
    {
        $phase = Phase::findOrFail($phase_id);
        if ($request->ajax()) {
            $sprints = $phase->sprints;
            $recordTotal = $sprints->count();
            $data = array(
                "draw" => intval($request['draw']),
                "recordsTotal" => intval($recordTotal),
                "recordsFiltered" => intval(2),
                "data" => $sprints
            );
            return response()->json($data);
        }
        return view('sprint.index', compact('phase'));
    }


    public function owner(Request $request)
    {
        $user = Auth::user();
        if ($request->ajax()) {
            if ($user->isAdmin()) {
                $sprints = Sprint::select('title', 'description', 'start_date', 'end_date', 'phase_id')->with(

                    ['phase:id,title,project_id', 'phase.project:id,title', 'phase.project.users' => function ($query) {
                        $query->where('admin', '1');
                    }]
                )->get();
            } else {
                $sprints = [];
                foreach ($user->projects as $project) {
                    foreach ($project->phases as $phase) {
                        $sprints[]=$phase->sprints()
                        ->with(['phase.project:id,title',
                        'phase.project.users'=>function($query){
                            $query->where('admin',1);
                        }])->get();
                    }
                }
                $sprints = collect($sprints)->collapse();

            }

            $recordTotal = $sprints->count();
            $data = array(
                "draw" => intval($request['draw']),
                "recordsTotal" => intval($recordTotal),
                "recordsFiltered" => intval(2),
                "data" => $sprints
            );
            return response()->json($data);
        }

        return view('sprint.owner');
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
    public function store(SprintRequest $request, $sprint_id = null)
    {
        if ($sprint_id) {
            $sprint = Sprint::findOrFail($sprint_id);
            $response = Gate::inspect('update', $sprint);
        } else {
            $response = Gate::inspect('create', [Sprint::class, $request->phase_id]);
        }

        if (!$response->allowed()) {
            return response()->json(['errors' => ['message' => $response->message()]], 403);
        }

        $user = Auth::user();
        $inputs = $request->all();
        $inputs['user_id'] = $user->id;
        $inputs['phase_id'] = $request->phase_id;
        $inputs['start_date'] = convertJalaliToGeorgian($request->start_date);
        $inputs['end_date'] = convertJalaliToGeorgian($request->end_date);
        if($request->task_confirm){
            $inputs['task_confirm']=1;
        }
        Sprint::updateOrCreate(['id' => $sprint_id], $inputs);
        if ($sprint_id) {
            return response()->json(['message' => 'اسپرینت مورد نظر آپدیت شد.']);
        }
        return response()->json(['message' => 'اسپرینت مورد نظر اضافه شد.']);
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
    public function edit(Sprint $sprint)
    {
        return response()->json($sprint);
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
    public function destroy(Sprint $sprint)
    {
        $sprint->delete();
        return response()->json(['message' => 'اسپرینت مورد نظر حذف شد.']);
    }
}
