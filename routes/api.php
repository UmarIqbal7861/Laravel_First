<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\usercontroller;
use App\Http\Controllers\logincontroller;
use App\Http\Controllers\verficontroller;
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



Route::get('verfi/email/123/ver/{mail}/{token}',[verficontroller::class, 'Verification']);
