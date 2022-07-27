<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request){

        if($request->ajax()){
            $users = User::select('id','fname','lname','mobile',
            'role','background_color','text_color')->get();
            $recordTotal = count($users);
            $data = array(
                "draw" => intval($request['draw']),
                "recordsTotal" => intval($recordTotal),
                "recordsFiltered" => intval(2),
                "data" => $users
            );

            return response()->json($data);
        }
        return view('user.index');

    }

    public function store(Request $request){

        $inputs = $request->all();
        $inputs['password'] = Hash::make($request->password);
        User::create($inputs);
        return response()->json(['message'=>'کاربر افزوده شد.']);

    }



    public function edit(User $user){

        return response()->json($user);
    }




    public function destroy(User $user){

        $user->delete();
        return response()->json(['message'=>'کاربر مورد نظر حذف شد.']);

    }


}
