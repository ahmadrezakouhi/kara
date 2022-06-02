<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequirementRequest;
use App\Models\Project;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DataTables;
class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        return view('owner.requirement.index');

    }

    public function getData(Request $request){
        $columns = array(
            // 0 => 'id',
            0 => 'title',
            1 => 'description',
            2 => 'created_at',

        );
        $queryFiltered = DB::table('requirements');
        // var_dump(Auth::user()->id);exit();
        // if ( Gate::allows('isAdmin') || Gate::allows('isUser') ){
        //     $queryFiltered =  $queryFiltered->where('id', Auth::user()->id);
        // }

        $recordsTotal = $queryFiltered->count();
        if (isset($request['sf'])){
            if($request['sf']['search-title'] != '')
                $queryFiltered =  $queryFiltered->where('title', $request['sf']['search-title']);
            // if($request['sf']['search-name'] != ''){
            //     $queryFiltered =  $queryFiltered->where('fname', 'like', '%' . $request['sf']['search-name'] . '%');
            //     $queryFiltered =  $queryFiltered->where('lname', 'like', '%' .   $request['sf']['search-name'] . '%');
            // }
        }
        $recordsFiltered = $queryFiltered->count();
        $data = $queryFiltered->orderBy($columns[$request['order'][0]['column']],$request['order'][0]['dir'])->offset($request['start'])->limit($request['length'])->get();

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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $requirement = Requirement::findOrFail($id);
        return response()->json($requirement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequirementRequest $request, $id)
    {
        $requirement = Requirement::findOrFail($id);
        $requirement->update($request->all());
        return response()->json($requirement);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $requirement = Requirement::findOrFail($id);
        // if($requirement->phases->count() == 0){
            $requirement->delete();

            return response()->json(['message'=>'نیازمندی مورد نظر با موفقیت حذف شد.']);
        // }

        // return response()->json(['msessage'=>'برای این نیاز مندی فاز تعریف شده است امکان حذف وجود ندارد.']);
    }
}
