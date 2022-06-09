<?php

namespace App\Http\Controllers;

use App\Http\Requests\SprintRequest;
use App\Models\Sprint;
use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function store(SprintRequest $request, $sprint_id=null)
    {
        $user = Auth::user();
        $inputs = $request->all();
        $inputs['user_id']=$user->id;
        $inputs['phase_id']=$request->phase_id;
        $inputs['start_date'] = convertJalaliToGeorgian($request->start_date);
        $inputs['end_date'] = convertJalaliToGeorgian($request->end_date);
        Sprint::updateOrCreate(['id'=>$sprint_id],$inputs);
        if($sprint_id){
            return response()->json(['message'=>'اسپرینت مورد نظر آپدیت شد.']);
        }
        return response()->json(['message'=>'اسپرینت مورد نظر اضافه شد.']);
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
    public function edit($id)
    {
        $sprint = Sprint::findOrFail($id);
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
    public function destroy($id)
    {
        Sprint::destroy($id);
        return response()->json(['message', 'اسپرینت مورد نظر حذف شد.']);
    }
}
