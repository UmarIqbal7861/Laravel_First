<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class comment_controller extends Controller
{
    /**
     * 
     */
    function comment(Request $req)
    {
        $data = DB::table('users')->where('remember_token', $req->token)->get();
        $check=count($data);
        if($check>0)    //if condition check user is login in or not
        {
            if($req->file!=null)
            {
                $file=$req->file('file')->store('post');    //store post 
            }
            else{
                $file=null;
            }
            $id1=$data[0]->U_id;
            $val=array('user_id'=>$id1,'post_id'=>$req->pid,'comment'=>$req->comment,'file'=>$file);
            DB::table('comments')->insert($val);       //database querie
            return response(['Message'=>'commenr Success']);
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
}
