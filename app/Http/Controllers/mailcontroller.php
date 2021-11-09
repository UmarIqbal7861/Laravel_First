<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class mailcontroller extends Controller
{
    function sendmail()
    {
        $details=[
            'title'=> 'Laravel1',
            'body'=> 'i love you'
        ];

        Mail::to("hussainhashmi1426@gmail.com")->send(new testmail($details));
        return "successfully mail send.";

    }
}
