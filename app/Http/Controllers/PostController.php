<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\PostValidation;
use App\Http\Requests\PostUpdateValidation;
use App\Http\Requests\PostDeleteValidation;
use App\Http\Requests\SearchPostValidation;
use App\Http\Resources\PostResource;



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
        try{
            $file=$req->file('file')->store('post');    //store post
            $val=array('user_id'=>$req->data->u_id,'file'=>$file,'access'=>$req->access);
            DB::table('posts')->insert($val);       //database querie
            return response()->json(['Message'=>'Post Success']);
        }catch(\Exception $error){
            return response()->json(['error'=>$error->getMessage()], 500);
        }
        
    }
    
    function postupdate(PostUpdateValidation $req)
    {
        try{
            $file=$req->file('file')->store('post');    //store post
            $data=DB::table('posts')->where(['p_id'=> $req->pid,'user_id'=>$req->data->u_id])->update(['file'=> $file,
                'access'=> $req->access,]);    //database querie   //database querie 
            if(!empty($data))
            {
                return response()->json(['Message'=>'Data Update']);
            }
            else{
                return response()->json(['Message'=>'Not Allow to update any other person post']);
            }
        }
        catch(\Exception $error){
            return response()->json(['error'=>$error->getMessage()], 500);
        }
       
    }

    function postdelete(PostDeleteValidation $req)
    {
        try{
            DB::table('comments')->where(['post_id'=>$req->pid,'user_id'=>$req->data->u_id])->delete();
            $check=DB::table('posts')->where(['p_id'=>$req->pid,'user_id'=>$req->data->u_id])->delete(); //database querie
            if($check)
            {
                return response()->json(['Message'=>'Data Delete']);
            }
            else{
                return response()->json(['Message'=>'Not Allow to Delete any other person post']);
            }
        }
        catch(\Exception $error){
            return response()->json(['error'=>$error->getMessage()], 500);
        }
        
    }
    /**
     * checkFriend function check friend exists in friend list
     */
    function checkFriend($user,$friend_id){
        try{
            $check = DB::table('friends')->where('user1',$user)->where('user2',$friend_id)->get();
            if (count($check) > 0){
                return true;
            }
            else{
                return false;
            }
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }

    function postread(Request $req)
    {
        try{
            $data=DB::table('posts')->where(['access'=>'public'])->get();    //access the public post
            foreach($data as $key)
            {
                $pid = $key->p_id;
                return new PostResource($key);
                $check=DB::table('comments')->where('post_id',$pid)->get();
                return new PostResource($check);
            }
            $data=DB::table('posts')->where(['access'=>'private'])->get();  //access the private post
            foreach($data as $key)
            {
                $id = $key->user_id;
                $pid = $key->p_id;
                if($this->checkFriend($req->data->u_id,$id))
                {
                    return new PostResource($key);
                    $check=DB::table('comments')->where('post_id',$pid)->get();
                    return new PostResource($check);
                }
            }
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }
    function postsearch()
    {
        
    }
}