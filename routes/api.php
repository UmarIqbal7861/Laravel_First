<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\SignUpController;
use App\Http\Controllers\UserController;
//use App\Http\Controllers\LoginController;
//use App\Http\Controllers\VerificationController;
//use App\Http\Controllers\LogoutController;
use App\Http\Controllers\AddFriendController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
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


Route::post('Signup',[UserController::class, 'signUp']);

Route::post('login',[UserController::class, 'login']);

Route::get('verfi/email/123/ver/{mail}/{token}',[UserController::class, 'Verification']);


Route::group(['middleware'=>"checktoken"],function()
{
    Route::post('addfriend',[AddFriendController::class, 'add_friend']);

    Route::post('update',[UserController::class, 'update']);

    Route::post('post',[PostController::class, 'post']);

    Route::post('postupdate',[PostController::class, 'postupdate']);

    Route::post('postdelete',[PostController::class, 'postdelete']);

    Route::post('postread',[PostController::class, 'read']);

    Route::post('comment',[CommentController::class, 'comment']);

    Route::post('logout',[UserController::class, 'logout']);
});