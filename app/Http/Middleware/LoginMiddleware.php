<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LoginMiddleware
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
        $users = DB::table('users')->where('email', $request->email)->first();
        if(!empty($users))
        {
            $verfi =$users->email_verified_at;
            if($verfi!=NULL)
            {
                $password = $users->password;
                $status = $users->status;
                $data=['password'=>$password,'status'=>$status];
                return $next($request->merge(["data"=>$data]));
            }
            else{
                return response()->json(['Message'=>'Email Not Verfiy']);
            }
        }
        else{
            return response()->json(['Message'=>'Email Not Exist']);
        }
        
    }
}
