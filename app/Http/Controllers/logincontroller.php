<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class logincontroller extends Controller
{
    public function login(Request $req)
    {
        $rules= [
            'Email' => 'required|email',
            'Password' => 'required|min:8|string', 
        ];
        
        $validator = Validator::make($req->all(),$rules);
        if($validator->fails()){
            return response()->json($validation->errors()->toJson(),400);
        }
        else
        {

            $user = new User;
        
            $user->email = $req->input('Email');
            $user->password = $req->input('Password');
            //$user->password = bcrypt($req->input('password')); // return hashed password

            //$jwt_token = JWT::;

            $users = DB::table('users')->where('email', $user->email)->where('password', $user->password)->get();

            
            if(!$users)
            {
                return response(['Message'=>'Data does not exists']);
            }
            else
            {
                DB::table('users')->where('email', $user->email)->update(['status'=> '1']);
                return response(['Message'=>'Now you are logged In']);
                
            }
        }
    }
}
