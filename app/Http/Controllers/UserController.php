<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $users = User::select(
                'id',
                'fname',
                'lname',
                'mobile',
                'role',
                'background_color',
                'text_color'
            )->get();
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

    public function store(UserRequest $request, $user_id = null)
    {

        $inputs = $request->all();
        if($user_id){
            $user = User::find($user_id);
            $inputs['password']=$user->password;
        }
        if ($request->has('changePassword')) {
            $inputs['password'] = Hash::make($request->password);
        }


        $inputs['user_id'] = Auth::id();
        unset($inputs['confirm_password']);
        User::updateOrCreate(['id' => $user_id], $inputs);
        return response()->json(['message' => 'کاربر افزوده شد.']);
    }



    public function edit(User $user)
    {

        return response()->json($user);
    }




    public function destroy(User $user)
    {

        $user->delete();
        return response()->json(['message' => 'کاربر مورد نظر حذف شد.']);
    }
}
