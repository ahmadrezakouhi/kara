<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function index(Request $request){

        if($request->ajax()){
            $users = User::select('id','fname','lname','phone','role')->get();
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


}
