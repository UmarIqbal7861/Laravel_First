<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\comment;

use App\Http\Requests\CommentValidation;
use App\Http\Requests\CommentDeleteValidation;
use App\Http\Requests\CommentUpdateValidation;

class CommentController extends Controller
{
    /**
     * this controller for use the comments table 
     */
    /**
     * this controller create comment,update comment,delete comment
     */
    function comment(CommentValidation $req)
    {
        try{
            $file=$req->file('file')->store('comment');    //store comment
            $value=array('user_id'=>$req->data->u_id,'post_id'=>$req->pid,'comment'=>$req->comment,'file'=>$file);
            DB::table('comments')->insert($value);       //database querie
            return response()->json(["message" => "Comment Success"]);
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }
    function commentupdate(CommentUpdateValidation $req)
    {
        try{
            $file=$req->file('file')->store('comment');    //store comment
            $check=DB::table('comments')->where(['c_id'=> $req->cid,'user_id'=>$req->data->u_id])->update(['comment'=>$req->comment,'file'=> $file]);    //database querie   //database querie 
            if($check)
            {
                return response()->json(["message" => "Data Update"]);
            }
            else{
                return response()->json(["message" => "Not Allow to Update any other person comment"]);
            }   
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
        
    }
    function commentDelete(CommentDeleteValidation $req)
    {
        try{
            $check=DB::table('comments')->where(['c_id'=> $req->cid , 'user_id' =>$req->data->u_id])->delete(); //database querie
            if($check)
            {
                return response()->json(['Message'=>'Data Delete']);
            }
            else{
                return response()->json(['Message'=>'Not Allow to Delet any other person post']);
            }
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }
}
