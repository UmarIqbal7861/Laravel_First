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
            $userc->password=Hash::make($req->input('Password'));
            $userc->gender=$req->input('Gender');
            $userc->status=0;
            $userc->token=$token=rand(100,1000);
            $result=$userc->save();
            if($result){
                $this->sendmail($mail,$token);
                return response()->json(['Message' => 'Signup Register'],200);
            }
            else{
                return response()->json(['Message'=>'Something went wrong..!!!'],400);
            }
        }
        
    }
    function sendmail($mail,$token)
    {
        $details=[
            'title'=> 'SignUp Verification',
            'body'=> 'This Link use for login http://127.0.0.1:8000/api/verfi/email/123/ver/'.$mail.'/'.$token
        ]; 
        Mail::to($mail)->send(new sendmail($details));
        return "successfully mail send.";
    }
}




