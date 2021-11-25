<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class Authenticatemiddele
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    /**
     * handle function check jwt toke is valid or not
     * if token is valid then proceed next request 
     */
    public function handle(Request $request, Closure $next)
    {
        $data = DB::table('users')->where('remember_token', $request->token)->first();//check token database querie
        if(!empty($data))
        {
            return $next($request->merge(["data"=>$data]));
        }
        else{
            return response()->json(['Message'=>'You Are Logout']);
        }
    }
}
