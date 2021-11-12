<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\SignUpValidation;
use App\Http\Requests\LogInValidation;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendmail;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


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
        $req->validated();
        $mail;
        $userc = new User;
        $userc->name=$req->input('Name');
        $userc->email=$req->input('Email');
        $mail=$req->input('Email');
        $userc->password=Hash::make($req->input('Password'));   //convert password in hash
        $userc->gender=$req->input('Gender');
        $data=$req->file('Profile')->store('Profile_pic');  //store profile pic
        $userc->profile=$data;
        $userc->status=0;
        $userc->token=$token=rand(100,1000);
        $result=$userc->save();     //database querie
        if($result){
            $mess=$this->sendMail($mail,$token);    //call send mail function 
            return response()->json(['Message' => 'Signup Register '. $mess],200);
        }
        else{
            return response()->json(['Message'=>'Something went wrong..!!!'],400);
        }
    }
    /**
     * sendmail function 
     * send mail with the link of verfiy link 
     */
    function sendMail($mail,$token)
    {
        $details=[
            'title'=> 'SignUp Verification',
            'body'=> 'This Link use for login http://127.0.0.1:8000/api/verfi/email/123/ver/'.$mail.'/'.$token
        ]; 
        Mail::to($mail)->send(new sendmail($details));
        return "Mail send.";
    }
    /**
     * Verification function
     * check the user email valid is not through the email link
     */
    function Verification($mail,$token)
    {
        $data=DB::table('users')->where('email', $mail)->where('token',$token)->get();     //database querie
        $check=count($data);
        if($check <= 0)
        {
            return "Your Email not Correct";
        }
        else{
            DB::table('users')->where('email', $mail)->update(['email_verified_at' => now()]);  //database querie
            DB::table('users')->where('email', $mail)->update(['updated_at' => now()]); //database querie
            return response(['Message' => 'Your Account has been Verified.']);
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
        $passsword = 0;
        $status = 0;
        $verfi=0;
        $req->validated();
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
    /**
     * update function 
     * get data from the postman (post) and updata name,gender,passwor and profile
     * get all data and update in data base
     */
    function update(Request $req)
    {
        $file=$req->file('file')->store('Profile_pic'); //store profile pic
        $pass=Hash::make($req->password);   //convert password in hash
        DB::table('users')->where('remember_token', $req->token)->update(['name'=> $req->name,
            'gender'=> $req->gender,'password'=> $pass,'profile'=>$file]); //database querie
        return response(['Message'=>'Data Update']);
    }
    /**
     * logout function 
     * logout function logout the user through the token
     * and update the status = 0 and remember_token= null
     */
    function logout(Request $req)
    {
        DB::table('users')->where('remember_token', $req->token)->update(['status'=> 0]);   //database querie
        DB::table('users')->where('remember_token', $req->token)->update(['remember_token'=> null]);    //database querie
        return response(['Message'=>'Logout']);
    }
    function forgetPassword()
    {

    }
}
