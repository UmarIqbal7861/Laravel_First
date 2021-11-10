<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class post_controller extends Controller
{
    /**
     * this controller for use the post table 
     */
    /**
     * this controller create post,update post,view all post ,and also delete post
     */
    function post(Request $req)
    {
        $data = DB::table('users')->where('remember_token', $req->token)->get();
        $check=count($data);
        if($check>0)    //if condition check user is login in or not
        {
            $id1=$data[0]->U_id;
            $file=$req->file('file')->store('post');    //store post
            $val=array('user_id'=>$id1,'file'=>$file,'access'=>$req->access);
            DB::table('posts')->insert($val);       //database querie
            return response(['Message'=>'Post Success']);
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
    function postupdate(Request $req)
    {
        $data = DB::table('users')->where('remember_token', $req->token)->get();
        $check=count($data);    //if condition check user is login in or not
        if($check>0)
        {
            $file=$req->file('file')->store('post');    //store post
            DB::table('posts')->where('P_id', $req->pid)->update(['file'=> $file,'access'=> $req->access,]);    //database querie   //database querie
            return response(['Message'=>'Data Update']);
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
    function postdelete(Request $req)
    {
        $data = DB::table('users')->where('remember_token', $req->token)->get();    //check token //database querie
        $check=count($data);    
        if($check>0)    //if condition check user is login in or not
        {
            DB::table('posts')->where('P_id', $req->pid)->delete(); //database querie
            return response(['Message'=>'Data Delete']);
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
    function read(Request $req)
    {
        $data = DB::table('users')->where('remember_token', $req->token)->get();    //database querie
        $check=count($data);    //if condition check user is login in or not
        if($check>0)
        {
            $id2=$data[0]->U_id;
            $data=DB::table('posts')->where('user_id', $id2)->get();    //database querie
            return response([$data]);
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
}
