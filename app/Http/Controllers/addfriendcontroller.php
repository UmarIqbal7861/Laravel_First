<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AddFriendValidation;
use App\Http\Requests\RemoveFriendValidation;
use App\Models\Friend;

class AddFriendController extends Controller
{
    /**
     * add_friend function
     * this function add one user to another user if the user exit 
     */
    function add_friend(AddFriendValidation $req)
    {
        try{
            $user_id=$req->data['user'];
            $friend_id=$req->data['friend'];
            $val=array('user1'=>$user_id,'user2'=>$friend_id);
            DB::table('friends')->insert($val);     //database querie
            return response()->json(["message" => "Friend Add."],200);
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }
    function removeFriend(RemoveFriendValidation $req)
    {
        try{
            $data = DB::table('users')->where('email', $req->email)->first();    //database querie
            if(!empty($data)) //if condition check 2nd user exit or not
            {   
                $user2_id=$data->u_id;
                $data=DB::table('friends')->where(['user1'=> $req->data->u_id , 'user2' =>$user2_id])->delete();
                if($data)
                {
                    return response()->json(["message" => "Friend Remove Conform."],200);
                }
                else{
                    return response()->json(["message" => "You are not Friend This user "],400);
                }
            }
            else{
                return response()->json(["message" => "Account Not exists "],404);
            }
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }
}