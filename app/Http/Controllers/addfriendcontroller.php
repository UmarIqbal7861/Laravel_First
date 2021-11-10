<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class addfriendcontroller extends Controller
{
    /**
     * add_friend function
     * this function add one user ti another user if the user exit 
     */
    function add_friend(Request $req)
    {
        $data = DB::table('users')->where('remember_token', $req->token)->get();    //database querie
        $check=count($data);    
        if($check>0)    //if condition check user is login in or not
        {
            $id1=$data[0]->U_id;
            $data1 = DB::table('users')->where('email', $req->email)->get();    //database querie
            $check1=count($data1);
            $ver=$data1[0]->email_verified_at;
            if(!empty($ver))    //if condition check the user mail is verfiy or not 
            {
                if($check1>0)   //if condition check 2nd user exit or not
                {
                    $id2=$data1[0]->U_id;
                    $val=array('user1'=>$id1,'user2'=>$id2);
                    DB::table('friends')->insert($val);     //database querie
                    return response(['Message'=>'Friend Add']);
                }
                else
                {
                    return response(['Message'=>'Friend not Found']);
                }
            }
            else{
                return response(['Message'=>'Friend not Found']);
            }
        }
        else{
            return response(['Message'=>'Please login Account']);
        }
    }
    
}
