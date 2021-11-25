<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SignupMiddleware
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
        $mail=$request->input('email');
        $data=DB::table('users')->where('email', $mail)->first();
        if(!empty($data))
        {
            return response()->json(['Message' => 'Email Already Exists.']);
        }
        else{
            return $next($request);
        }
        
    }
}
