<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class verficontroller extends Controller
{
    function Verification($mail,$token)
    {
        $data=DB::table('users')->where('email', $mail)->where('token',$token)->get();
        $check=count($data);
        if($check <= 0)
        {
            return "Your Email not Correct";
        }
        else{
            DB::table('users')->where('email', $mail)->update(['email_verified_at' => now()]);
            DB::table('users')->where('email', $mail)->update(['updated_at' => now()]);
            return response(['Message' => 'Your Account has been Verified.']);
        }
    }
}
