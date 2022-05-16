<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests\StoreUserRequest;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return \View::make('user.index')
        ->with('user');
    }
    public function getAll(Request $request){
        $user = User::get();
        return response()->json($user);
    }
    public function getData(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'fname',
            2 => 'mobile',
            3 => 'phone',
            4 => 'email',
            5 => 'status',
        );

        $recordsTotal = DB::table('users')->count();
        $queryFiltered = DB::table('users');
        if (isset($request['sf'])){
            if($request['sf']['search-mobile'] != '')
                $queryFiltered =  $queryFiltered->where('mobile', $request['sf']['search-mobile']);
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

    public function getDataForProject(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'fname',
            2 => 'mobile',
            3 => 'phone',
            // 4 => 'email',
            // 5 => 'status',
        );

        $recordsTotal = DB::table('users')->count();
        $queryFiltered = DB::table('users');
        // if (isset($request['sf'])){
        //     if($request['sf']['search-mobile'] != '')
        //         $queryFiltered =  $queryFiltered->where('mobile', $request['sf']['search-mobile']);
        //     if($request['sf']['search-name'] != ''){
        //         $queryFiltered =  $queryFiltered->where('fname', 'like', '%' . $request['sf']['search-name'] . '%');
        //         $queryFiltered =  $queryFiltered->where('lname', 'like', '%' .   $request['sf']['search-name'] . '%');
        //     }
        // }
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
        return \View::make('user.create');
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
        $rules = array(
            'fname'       => 'required',
            'lname'       => 'required',
            'email'       => 'required|email',
        );
        
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return redirect('user/create')
                        ->withErrors($validator)
                        ->withInput();
        } else {
            // store
            $user = new User;
            $user->fname       = $request->fname; 
            $user->lname       = $request->lname;
            $user->email       = $request->email; 
            $user->mobile      = $request->mobile;
            $user->phone      = $request->phone;
            $user->user_id     = Auth::user()->id;
            $user->role        = $request->role;
            $user->password    = Hash::make($request->password);
            $user->save();

            // redirect
            $request->session()->flash('message', 'کاربر با موفقیت ثبت شد!');
            return redirect('user');
        }
    }

    public function addUser(Request $request)
    {
        //
        $rules = array(
            'fname'       => 'required',
            'lname'       => 'required',
            'email'       => 'required|email',
        );
        
        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return -1;
        } else {
            // store
            $user = new User;
            $user->fname       = $request->fname; 
            $user->lname       = $request->lname;
            $user->email       = $request->email; 
            $user->mobile      = $request->mobile;
            $user->phone      = $request->phone;
            $user->user_id     = Auth::user()->id;
            $user->role        = $request->role;
            $user->password    = Hash::make($request->password);
            $user->save();

            // redirect
            $request->session()->flash('message', 'کاربر با موفقیت ثبت شد!');
            return response()->json($user);
        }
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
        $user = User::find($id);
        return \View::make('user.show')
        ->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::find($id);
        return \View::make('user.edit')
        ->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        if (Gate::allows('isManager') ||  Gate::allows('isAdmin')  || $request->user()->can('update',$user)) {
            $rules = array(
                'fname'    => 'required', 
                'lname'    => 'required',
                'email'       => 'required|email',
            );
            $validator = Validator::make($request->all(), $rules);

            // process the login
            if ($validator->fails()) {
                return redirect('user/' . $user->id . '/edit')
                    ->withErrors($validator)
                    ->withInput();
            } else {
                // store
                $user = User::find($user->id);
                $user->fname       = $request->fname; 
                $user->lname       = $request->lname;
                $user->email       = $request->email; 
                // $user->description = $request->description;
                $user->mobile      = $request->mobile;
                $user->phone       = $request->phone;
                $user->role        = $request->role;
                // $user->password         =  Hash::make($request->password);
                $user->save();

                // redirect
                $request->session()->flash('message', 'کاربر با موفقیت بروز رسانی شد!');
                return redirect('user');
            }
        }else{
            $request->session()->flash('message', 'عدم دسترسی!');
            return redirect('user');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request,User $user)
    {
        //
        if (Gate::allows('isManager') ||  Gate::allows('isAdmin')  || $request->user()->can('delete',$user)) {
            //
                // delete
                $user = user::find($user->id);
                $user->delete();
    
                // redirect
                $request->session()->flash('message', 'کاربر با موفقیت بروز رسانی شد!');
                return true;
            }else{
                $request->session()->flash('message', 'عدم دسترسی برای حذف!');
                abort(403);
            }
    }
}
