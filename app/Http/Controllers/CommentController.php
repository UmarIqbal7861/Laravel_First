<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\comment;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CommentValidation;

class CommentController extends Controller
{
    /**
     * 
     */
    function comment(CommentValidation $req)
    {
        $req->validated();
        $data = DB::table('users')->where('remember_token', $req->token)->get();
        $check=count($data);
        if($check>0)    //if condition check user is login in or not
        {
            $id1=$data[0]->u_id;
            $val=array('user_id'=>$id1,'post_id'=>$req->pid,'comment'=>$req->comment,'file'=>$req->file);
            DB::table('comments')->insert($val);       //database querie
            return response(['Message'=>'Comment Success']);
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
     function postupdate(Request $req)
    {

        $rules= [
            'pid' => 'required',
            'file' => 'required',
            'access' => 'required',
        ];
        
        $validator = Validator::make($req->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        else
        {
            $file=$req->file('file')->store('post');    //store post
            DB::table('posts')->where('P_id', $req->pid)->update(['file'=> $file,'access'=> $req->access,]);    //database querie   //database querie
            return response(['Message'=>'Data Update']);
        }
    }


    function postdelete(Request $req)
    {

        $rules= [
            'pid' => 'required',
        ];
        
        $validator = Validator::make($req->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        else
        {
            DB::table('posts')->where('P_id', $req->pid)->delete(); //database querie
            return response(['Message'=>'Data Delete']);

        }
    }
}
