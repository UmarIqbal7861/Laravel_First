<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class logoutcontroller extends Controller
{
    /**
     * logout function 
     * logout function logout the user through the token
     * and update the status = 0 and remember_token= null
     */
    function logout(Request $req)
    {
        $data = DB::table('users')->where('remember_token', $req->token)->get();//check token database querie
        $check=count($data);
        if($check>0)
        {
            DB::table('users')->where('remember_token', $req->token)->update(['status'=> 0]);   //database querie
            DB::table('users')->where('remember_token', $req->token)->update(['remember_token'=> null]);    //database querie
            return response(['Message'=>'Logout']);
        }
        else{
            return response(['Message'=>'Token not Found']);
        }
    }
}
