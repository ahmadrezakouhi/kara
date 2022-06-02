<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequirementController extends Controller
{


    public function index($id){
        $project = Project::find($id);
        return view('requirement.index',compact('project'));
    }

    public function store(Request $request){
        $user = Auth::user();
        Requirement::create([
            'user_id'=>$user->id,
            'project_id'=>$request->input('project_id'),
            'title'=>$request->input('title'),
            'description'=>$request->input('description')
        ]);
        return response()->json(['message'=>'نیازمندی جدید افزوده شد.']);

    }
}
