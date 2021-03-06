<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class myprovider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success',function($data,$status_code){

            return response()->json([            
                'success' => true,            
                'message' => $data['message'],            
                'data'    => $data['data'],        
            ],$status_code);
            });        
            Response::macro('error',function($data, $status_code){                    
                return response()->json([            
                    'success' => false,            
                    'message' => $data['message'],            
                    'error'   => $data['error'],        
                ],$status_code);        
            });
    }
}
