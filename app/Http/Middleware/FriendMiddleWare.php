<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Closure;
use Illuminate\Http\Request;

class FriendMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user_id=$request->data->u_id;
        $find = DB::table('users')->where('email', $request->email)->first();
        if(!empty($find))
        {
            $ver=$find->email_verified_at;
            if($ver!=NULL)
            {
                $friend_id=$find->u_id;
                if($user_id!=$friend_id)
                {
                    $search=DB::table('friends')->where('user1', $user_id)->where('user2',$friend_id)->first();
                    if(!empty($search))
                    {
                        return response()->json(['Message'=>'You Are Already Friends']);
                    }
                    else{
                        $data=['user'=>$user_id,'friend'=>$friend_id];
                        return $next($request->merge(["data"=>$data]));
                    }
                }
                else{
                    return response()->json(['Message'=>'Friend not Found']);
                }
            }
            else{
                return response()->json(['Message'=>'Friend not Found']);
            }
        }
        else{
            return response()->json(['Message'=>'Friend not Found']);
        }
        
    }
}
