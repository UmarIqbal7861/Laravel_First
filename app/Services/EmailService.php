<?php
namespace App\Services;

use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Config;


class EmailService
{    
    /**
     * sendmail function 
     * send mail with the link of verfiy link 
     */
    function sendMail($send_mail,$token)
    {
        $details=[
            'title'=> 'SignUp Verification',
            'body'=> 'This Link use for login http://127.0.0.1:8000/user/verfi/email/123/ver/'.$send_mail.'/'.$token
        ];   
        dispatch(new SendEmailJob($send_mail,$details));
        return "Mail Send";
    }

        /**
     * sendmail function 
     * send mail with the link of for forget the password 
     */
    function sendMailForgetPassword($mail,$otp)
    {
        $details=[
            'title'=> 'Forget Password Verification',
            'body'=> 'Your OTP is '. $otp . ' Please copy and paste the change Password Api'
        ]; 
        dispatch(new SendEmailJob($mail,$details));
        return "Mail send.";
    }
}