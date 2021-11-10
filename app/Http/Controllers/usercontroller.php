<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendmail;
class usercontroller extends Controller
{
    public $mail;
    /**
     * sign_up function
     * sign up get data through postman and chech email already exit or not 
     * if not exit then check all the add and update the user table
     */
    function sign_up(Request $req)
    {
        $rules= [
            'Name' => 'required|string',
            'Email' => 'required|email|unique:users',
            'Password' => 'required|min:8|max:20',
            'Gender' => 'required|Alpha', 
        ];
        
        $validator = Validator::make($req->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        else
        {
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
                $mess=$this->sendmail($mail,$token);    //call send mail function 
                return response()->json(['Message' => 'Signup Register '. $mess],200);
            }
            else{
                return response()->json(['Message'=>'Something went wrong..!!!'],400);
            }
        }
        
    }
    /**
     * sendmail function 
     * send mail with the link of verfiy link 
     */
    function sendmail($mail,$token)
    {
        $details=[
            'title'=> 'SignUp Verification',
            'body'=> 'This Link use for login http://127.0.0.1:8000/api/verfi/email/123/ver/'.$mail.'/'.$token
        ]; 
        Mail::to($mail)->send(new sendmail($details));
        return "Mail send.";
    }
}




