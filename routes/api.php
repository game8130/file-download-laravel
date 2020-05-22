<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1/')->middleware('api')->group(function () {
    Route::namespace('User')->group(function () {
        // 登入
        Route::post('login', 'UsersController@login');
        // 註冊
        Route::post('register', 'UsersController@register');
    });
    // 登入後
    Route::middleware('auth.jwt')->group(function () {

    });
});
