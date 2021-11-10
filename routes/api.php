<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\usercontroller;
use App\Http\Controllers\logincontroller;
use App\Http\Controllers\verficontroller;
use App\Http\Controllers\logoutcontroller;
use App\Http\Controllers\addfriendcontroller;
use App\Http\Controllers\updatecontroller;
use App\Http\Controllers\post_controller;
use App\Http\Controllers\comment_controller;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('Signup',[usercontroller::class, 'sign_up']);

Route::post('login',[logincontroller::class, 'login']);

Route::post('logout',[logoutcontroller::class, 'logout']);

Route::post('addfriend',[addfriendcontroller::class, 'add_friend']);

Route::post('update',[updatecontroller::class, 'update']);

Route::post('post',[post_controller::class, 'post']);

Route::post('postupdate',[post_controller::class, 'postupdate']);

Route::post('postdelete',[post_controller::class, 'postdelete']);

Route::post('postread',[post_controller::class, 'read']);

Route::get('verfi/email/123/ver/{mail}/{token}',[verficontroller::class, 'Verification']);

Route::post('comment',[comment_controller::class, 'comment']);
