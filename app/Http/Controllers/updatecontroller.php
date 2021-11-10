<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class updatecontroller extends Controller
{
    /**
     * update function 
     * get data from the postman (post) and updata name,gender,passwor and profile
     * get all data and update in data base
     */
    function update(Request $req)
    {
        $data = DB::table('users')->where('remember_token', $req->token)->get();    //check token database querie
        $check=count($data);    //check data exist
        if($check>0)
        {
            $file=$req->file('file')->store('Profile_pic'); //store profile pic
            $pass=Hash::make($req->password);   //convert password in hash
            DB::table('users')->where('remember_token', $req->token)->update(['name'=> $req->name,
                'gender'=> $req->gender,'password'=> $pass,'profile'=>$file]); //database querie
            return response(['Message'=>'Data Update']);
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
}
