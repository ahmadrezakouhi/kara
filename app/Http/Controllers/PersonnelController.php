<?php
namespace App\Http\Controllers;
use App\Http\Requests\StorePersonnelRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\Personnel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         // get all the personnel
        //  $personnel = personnel::all();

         // load the view and pass the personnel
         return View::make('personnel.index')
             ->with('personnel' );
    }

    public function getData(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'fname',
            2 => 'mellicode',
            3 => 'mobile1',
            4 => 'mobile2',
            5 => 'address',
            6 => 'status',
        );

        
        $recordsTotal = DB::table('personnels')->count();
        $queryFiltered = DB::table('personnels');
        if (isset($request['sf'])){
            if($request['sf']['search-mellicode'] != '')
                $queryFiltered =  $queryFiltered->where('mellicode', $request['sf']['search-mellicode']);
            if($request['sf']['search-name'] != ''){
                $queryFiltered =  $queryFiltered->where('fname', 'like', '%' . $request['sf']['search-name'] . '%');
                $queryFiltered =  $queryFiltered->where('lname', 'like', '%' .   $request['sf']['search-name'] . '%');
            }
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return View::make('personnel.create');
    }

    
    /**
     * Store a new personnel.
     *
     * @param  Request  $request
     * @return Response
     */
    
    public function store(StorePersonnelRequest $request)
    {
        //
         // validate
        // read more on validation at http://laravel.com/docs/validation
        
        $rules = array(
            'fname'       => 'required',
            'mellicode'       => 'required',
        );
        
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return redirect('personnel/create')
                        ->withErrors($validator)
                        ->withInput();
        } else {
            // store
            $personnel = new Personnel;
            $personnel->fname       = $request->fname; 
            $personnel->lname       = $request->lname;
            $personnel->mellicode   = $request->mellicode;
            $personnel->email       = $request->email; 
            // $personnel->description = $request->description;
            $personnel->mobile1     = $request->mobile1;
            $personnel->mobile2     = $request->mobile2;
            $personnel->sex         = $request->sex;
            $personnel->status      = $request->status;
            $personnel->address     = $request->address; 
            $personnel->user_id     = Auth::user()->id;
            $personnel->save();

            // redirect
            $request->session()->flash('message', 'پرسنل با موفقیت ثبت شد!');
            return redirect('personnel');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Personnel  $personnel
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
         // get the personnel
         $personnel = Personnel::find($id);

         // show the view and pass the personnel to it
         return View::make('personnel.show')
             ->with('personnel', $personnel);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Personnel  $personnel
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        // get the personnel
        $personnel = Personnel::find($id);

        // show the edit form and pass the personnel
        return View::make('personnel.edit')
            ->with('personnel', $personnel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Models\Personnel  $personnel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Personnel $personnel)
    {
        //
         // validate
        // read more on validation at http://laravel.com/docs/validation
        if (Gate::allows('isManager') ||  Gate::allows('isAdmin')  || $request->user()->can('update',$personnel)) {
            $rules = array(
                'fname'       => 'required',
                'mellicode'   => 'required'
            );
            $validator = Validator::make($request->all(), $rules);

            // process the login
            if ($validator->fails()) {
                return redirect('personnel/' . $personnel->id . '/edit')
                    ->withErrors($validator)
                    ->withInput();
            } else {
                // store
                $personnel = Personnel::find($personnel->id);
                $personnel->fname       = $request->fname; 
                $personnel->lname       = $request->lname;
                $personnel->mellicode   = $request->mellicode;
                $personnel->email       = $request->email; 
                // $personnel->description = $request->description;
                $personnel->mobile1     = $request->mobile1;
                $personnel->mobile2     = $request->mobile2;
                $personnel->sex         = $request->sex;
                $personnel->status      = $request->status;
                $personnel->address     = $request->address;
                $personnel->save();

                // redirect
                $request->session()->flash('message', 'کاربر با موفقیت بروز رسانی شد!');
                return redirect('personnel');
            }
        }else{
            $request->session()->flash('message', 'عدم دسترسی!');
            return redirect('personnel');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Personnel  $personnel
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function destroy(Request $request, Personnel $personnel)
    {
        
        if (Gate::allows('isManager') ||  Gate::allows('isAdmin')  || $request->user()->can('delete',$personnel)) {
        //
            // delete
            // $personnel = personnel::find($personnel->id);
            $personnel->delete();

            // redirect
            $request->session()->flash('message', 'کاربر با موفقیت بروز رسانی شد!');
            return true;
        }else{
            $request->session()->flash('message', 'عدم دسترسی برای حذف!');
            abort(403);
        }
        //  return redirect('personnel');
    }
}
