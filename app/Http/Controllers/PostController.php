<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PostValidation;
use App\Http\Requests\PostUpdateValidation;
use App\Http\Requests\PostDeleteValidation;



class PostController extends Controller
{
    /**
     * this controller for use the post table 
     */
    /**
     * this controller create post,update post,view all post ,and also delete post
     */
    function post(PostValidation $req)
    {
        $req->validated();
        $data = DB::table('users')->where('remember_token', $req->token)->get();
        $check=count($data);
        if($check>0)    //if condition check user is login in or not
        {
            $id1=$data[0]->u_id;
            $file=$req->file('file')->store('post');    //store post
            $val=array('user_id'=>$id1,'file'=>$file,'access'=>$req->access);
            DB::table('posts')->insert($val);       //database querie
            return response(['Message'=>'Post Success']);
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
    function postupdate(PostUpdateValidation $req)
    {
        $req->validated();
        $file=$req->file('file')->store('post');    //store post
        DB::table('posts')->where('P_id', $req->pid)->update(['file'=> $file,'access'=> $req->access,]);    //database querie   //database querie
        return response(['Message'=>'Data Update']);
    }


    function postdelete(PostDeleteValidation $req)
    {

        $req->validated();
        DB::table('posts')->where('P_id', $req->pid)->delete(); //database querie
        return response(['Message'=>'Data Delete']);

    }
    function read(Request $req)
    {
        
        $data = DB::table('users')->where('remember_token', $req->token)->get();    //database querie
        $check=count($data);    //if condition check user is login in or not
        if($check>0)
        {
            $id2=$data[0]->u_id;
            $data=DB::table('posts')->where('user_id', $id2)->get();    //database querie
            return response([$data]);
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
}
