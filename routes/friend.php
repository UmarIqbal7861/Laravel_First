<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddFriendController;

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

    Route::post('addfriend',[AddFriendController::class, 'add_friend'])->middleware('checktoken')->middleware('friend');

    Route::post('removefriend',[AddFriendController::class, 'removeFriend'])->middleware('checktoken');