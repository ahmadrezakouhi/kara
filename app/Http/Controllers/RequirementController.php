<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequirementRequest;
use App\Models\Project;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RequirementController extends Controller
{


    public function index(Request $request, $id)
    {
        $project = Project::with('requirements.phase')->findOrFail($id);
        $phases = $project->phases;
        if ($request->ajax()) {
            $requirements = $project->requirements;
            $recordTotal = $requirements->count();
            $data = array(
                "draw" => intval($request['draw']),
                "recordsTotal" => intval($recordTotal),
                "recordsFiltered" => intval(2),
                "data" => $requirements
            );
            return response()->json($data);
        }
        return view('requirement.index', compact('project', 'phases'));
    }

    public function store(RequirementRequest $request, $requirement_id = null)
    {
        if ($requirement_id) {
            $requirement = Requirement::find($requirement_id);
            $response = Gate::inspect('update', $requirement);
        } else {
            $response = Gate::inspect('create', [Requirement::class,$request->project_id]);
        }
        if(!$response->allow()){
            return response()->json(['errors'=>['messgae'=>$response->message()]],403);
        }

        $user = Auth::user();
        $inputs = $request->all();
        $inputs['user_id'] = $user->id;
        $inputs['project_id'] = $request->project_id;
        Requirement::updateOrCreate(['id' => $requirement_id], $inputs);
        if ($requirement_id) {
            response()->json(['message' => ' نیازمندی مورد نظر به روز شد.']);
        }
        return response()->json(['message' => 'نیازمندی جدید افزوده شد.']);

    }

    public function edit(Requirement $requirement)
    {

        return response()->json($requirement, 200);
    }


    public function destroy(Requirement $requirement)
    {
        $requirement->delete();
        return response()->json(['message' => 'نیازمندی مورد نظر حذف شد.']);
    }



    public function add_phase(Request $request, Requirement $requirement)
    {
            $requirement->update([
            'phase_id' => $request->phase_id
        ]);

        return response()->json(['message' => 'نیازمندی به فاز لینک شد.']);
    }
}
