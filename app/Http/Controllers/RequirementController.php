<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequirementRequest;
use App\Models\Project;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequirementController extends Controller
{


    public function index(Request $request, $id)
    {
        $project = Project::find($id);
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
        return view('requirement.index', compact('project'));
    }

    public function store(RequirementRequest $request, $requirement_id=null)
    {

        $user = Auth::user();
        $inputs = $request->all();
        $inputs['user_id'] = $user->id;
        $inputs['project_id'] = $request->project_id;
        Requirement::updateOrCreate(['id' => $requirement_id], $inputs);
        if($requirement_id){
            response()->json(['message'=>'نیازمندی مورد نظر ']);
        }
        return response()->json(['message' => 'نیازمندی جدید افزوده شد.']);
    }

    public function edit($id)
    {
        $requirement = Requirement::select('id', 'title', 'description')->findOrFail($id);
        return response()->json($requirement,200);
    }


    public function destroy($id)
    {
        Requirement::destroy($id);
        return response()->json(['message' => 'نیازمندی مورد نظر حذف شد.']);
    }
}
