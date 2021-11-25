<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\SignUpValidation;
use App\Http\Requests\LogInValidation;
use App\Http\Requests\ForgetValidation;
use App\Http\Requests\ChangePasswordValidation;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Jobs\SendEmailJob;
use App\Services\jwtService;
use App\Mail\QueueEmail;
use App\Services\EmailService;


class UserController extends Controller
{
    public $mail;
    /**
     * sign_up function
     * sign up get data through postman and chech email already exit or not 
     * if not exit then check all the add and update the user table
     */
    function signUp(SignUpValidation $req)
    {
        try{
            $mail;
            $userc = new User;
            $userc->name=$req->input('name');
            $userc->email=$req->input('email');
            $mail=$req->input('email');
            $userc->password=Hash::make($req->input('password'));   //convert password in hash
            $userc->gender=$req->input('gender');
            $data=$req->file('profile')->store('Profile_pic');  //store profile pic
            $userc->profile=$data;
            $userc->status=0;
            $userc->token=$token=rand(100,1000);
            $result=$userc->save();     //database query
            if($result)
            {
                $mail_sender = new EmailService();
                $mess= $mail_sender->sendMail($mail,$token);    //call send mail function 
                return response()->json(['Message' => 'Signup Register '. $mess]);
            }
            else{
                return response()->json(['Message'=>'Something went wrong..!!!'],400);
            }
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }

    /**
     * this jwtToken generate the jwt toke and return jwt Token
     */
    function jwtToken()
    {
        $jwt_conn = new jwtService();
        return $jwt = $jwt_conn->get_jwt();
    }
    /**
     * Verification function
     * check the user email valid is not through the email link
     */
    function Verification($mail,$token)
    {
        try{
            $data=DB::table('users')->where('email', $mail)->where('token',$token)->first();  
            //dd($data);   //database query
            if($data==null)
            {
                return response()->json(['Message' => 'Your Email Not Correct.'],404);
            }
            else{
                DB::table('users')->where('email', $mail)->update(['email_verified_at' => now()]);  //database querie
                DB::table('users')->where('email', $mail)->update(['updated_at' => now()]); //database query
                return response()->json(['Message' => 'Your Account has been Verified.'],200);
            }
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }
    /**
     * login function 
     * this function login the user if the user exit in the database and also verfiy the account
     * when the user login we generate jwt token and store in the database
     * this function get data through the postman in the post methord
     */
    public function login(LogInValidation $req)
    {
        try{
            $password = 'null';
            $status = 0;
            $verfi=0;
            $user = new User;
            $user->email = $req->input('email');
            $user->password = $req->input('password');
            //$users = DB::table('users')->where('email', $user->email)->first();
            $password=$req->data['password'];
            $status=$req->data['status'];
            if(Hash::check($user->password,$password))
            {
                if($status == 1)
                {
                    $jwt=$this->jwtToken();
                    DB::table('users')->where('email', $user->email)->update(['remember_token'=> $jwt]); 
                    return response()->json(['Message'=>'You are already logged in..!','Access_Token'=>$jwt],200);                    
                }
                else{
                    $jwt=$this->jwtToken();
                    DB::table('users')->where('email', $user->email)->update(['remember_token'=> $jwt]);    //database query
                    DB::table('users')->where('email', $user->email)->update(['status'=> '1']);     //database query
                    return response()->json(['Message'=>'Now you are logged In','Access_Token'=>$jwt],200);     //return response
                }
            }
            else{
                return response()->json(['Message'=>'Data does not exists'],404);                
            }
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
        
    }
    /**
     * update function 
     * get data from the postman (post) and updata name,gender,passwor and profile
     * get all data and update in data base
     */
    function update(Request $req)
    {
        try{
            $search=DB::table('users')->where('remember_token', $req->token)->first();
            if($req->name!=Null)
            {
                DB::table('users')->where('remember_token', $req->token)->update(['name'=> $req->name]);
                return response()->json(['Message'=>'Data Update'],200);
            }
            if($req->gender!=Null)
            {
                DB::table('users')->where('remember_token', $req->token)->update(['gender'=> $req->gender]);
                return response()->json(['Message'=>'Data Update'],200);
            }
            if($req->password!=Null)
            {
                $pass=Hash::make($req->password);   //convert password in hash
                DB::table('users')->where('remember_token', $req->token)->update(['password'=> $pass]);
                return response()->json(['Message'=>'Data Update'],200);
            }
            if($req->file!=Null)
            {
                $file=$req->file('file')->store('Profile_pic'); //store profile pic
                $pass=Hash::make($req->password);   //convert password in hash
                DB::table('users')->where('remember_token', $req->token)->update(['profile'=>$file]); //database querie
                return response()->json(['Message'=>'Data Update'],200);
            }
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }
    /**
     * logout function 
     * logout function logout the user through the token
     * and update the status = 0 and remember_token= null
     */
    function logout(Request $req)
    {
        try{
            DB::table('users')->where('remember_token', $req->token)->update(['status'=> 0]);   //database querie
            DB::table('users')->where('remember_token', $req->token)->update(['remember_token'=> null]);    //database querie
            return response()->json(['Message'=>'Logout'],200);
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }
    /**
     * forgetPassword function forget the password through otp 
     * generate otp and send sendMailForgetPassword function 
     */
    function forgetPassword(ForgetValidation $req)
    {
        try{
            $mail=$req->email;
            $data = DB::table('users')->where('email', $mail)->first();
            if(!empty($data))
            {

                $verfi =$data->email_verified_at;
                if(!empty($verfi))
                {
                    $otp=rand(1000,9999);
                    DB::table('users')->where('email', $mail)->update(['token'=> $otp]);
                    $mail_sender = new EmailService();
                    $mess= $mail_sender->sendMailForgetPassword($mail,$otp);  
                    return response($mess);
                }
                else{
                    return response()->json(['Message'=>'User not Exists'],404);
                }
            }
            else{
                return response()->json(['Message'=>'User not Exists'],404);
            }
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }

    /**
     * changePassword function change password if otp match
     */
    function changePassword(ChangePasswordValidation $req)
    {
        try{
            $mail=$req->email;
            $token=$req->otp;
            $pass=Hash::make($req->password);
            $data = DB::table('users')->where('email', $mail)->first();
            if(!empty($data))
            {
                $token1 =$data->token;

                if($token1==$token)
                {
                    DB::table('users')->where('email', $mail)->update(['password'=> $pass]);
                    return response()->json(['Message'=>'Password Updated : '],200);
                }
                else{
                    return response()->json(['Message'=>'Otp Does Not Match : '],404);
                }
            }
            else{
                return response()->json(['Message'=>'Please Enter Valid Mail : '],404); 
            }
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }

    function user_details_and_posts_details(Request $req)
    {
        try{
            $token = $req->token;
            $data = DB::table('users')->where(['remember_token' => $token])->get();
            $uid = $data[0]->u_id;
            $check = count($data);
            if($check >0)
            {
                $data = User::with(['AllUserPost', 'AllUsersPostComments'])->where('u_id', $uid)->get();
                return new UserResource($data);
            }
            else{
                return response()->json(['Message' => 'Token not found orexpired...!!!!'],404);
            }
        }
        catch(\Exception $error)
        {
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }
}
