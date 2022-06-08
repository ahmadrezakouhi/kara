<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class PhaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request ,$project_id)
    {
        $project = Project::findOrFail($project_id);
        if($request->ajax()){
            $phases = $project->phases;
            return response()->json($phases);
        }
        return view('phase.index',compact('project'));

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$phase_id)
    {
        $user = Auth::user();
        $inputs = $request->all();
        $inputs['user_id']=$user->id;
        Phase::updateOrCreate(['id'=>$phase_id],$inputs);
        return response()->json(['message'=>'فاز جدید افزوده شد.']);

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $phase = Phase::findOrFail($id);
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
    public function destroy($id)
    {
        Phase::destroy($id);
        return response()->json(['message'=>'فاز مورد نظر حذف شد.']);
    }
}
