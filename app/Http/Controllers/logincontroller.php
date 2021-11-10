<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class logincontroller extends Controller
{
    /**
     * login function 
     * this function login the user if the user exit in the database and also verfiy the account
     * when the user login we generate jwt token and store in the database
     * this function get data through the postman in the post methord
     */
    public function login(Request $req)
    {
        $passsword = 0;
        $status = 0;
        $verfi=0;

        $rules= [
            'Email' => 'required|email',
            'Password' => 'required|min:8|string', 
        ];
        
        $validator = Validator::make($req->all(),$rules);
        if($validator->fails()){
            return response()->json($validation->errors()->toJson(),400);
        }
        else
        {
            $user = new User;
            $user->email = $req->input('Email');
            $user->password = $req->input('Password');
            $users = DB::table('users')->where('email', $user->email)->get();
            foreach ($users as $key)
            {
                $passsword = $key->password;
                $status = $key->status;
                $verfi =$key->email_verified_at;
            }
            if(!empty($verfi))
            {
                if(Hash::check($user->password,$passsword))
                {
                    if($status == 1)
                    {
                        return response(['Message'=>'You are already logged in..!']);                    
                    }
                    /**
                     * this else generate the jwt toke and then store in $jwt and then store in data base
                     */
                    else
                    {
                        $key = "umari4042";
                        $payload = array(
                            "iss" => "localhost",
                            "aud" => "users",
                            "iat" => time(),
                            "exp" => time()+1800,
                            "nbf" => 1357000000
                        );

                        $jwt = JWT::encode($payload, $key, 'HS256');

                        DB::table('users')->where('email', $user->email)->update(['remember_token'=> $jwt]);    //database queries
                        DB::table('users')->where('email', $user->email)->update(['status'=> '1']);     //database queries
                        return response(['Message'=>'Now you are logged In','Access_Token'=>$jwt]);     //return response
                    }

                }
                else
                {
                    return response(['Message'=>'Data does not exists']);                
                }
            }
            else{
                return response(['Message'=>'Your Email is not Verified. Please Verify your email first.']); 
            }
            
        }
    }
}
