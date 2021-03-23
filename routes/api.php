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

Route::prefix('v1/')->namespace('Api')->group(function () {
    // 驗證碼
    Route::get('/captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptchaApi');
    Route::namespace('User')->group(function () {
        // 登入
        Route::post('login', 'UsersController@login');
        // 註冊
        Route::post('register', 'UsersController@register');
    });
    // 登入後
    Route::middleware(['auth.jwt', 'auth'])->group(function () {
        Route::namespace('User')->group(function () {
            // 登入後檢查
            Route::get('/auth', 'UsersController@information');
            // 系統登出
            Route::post('/logout', 'UsersController@logout');
        });
        /*
        |--------------------------------------------------------------------------
        | 人事管理系統
        |--------------------------------------------------------------------------
        */
        // 人員管理
        Route::prefix('user')->namespace('User')->middleware('permission:1001')->group(function () {
            // 列表
            Route::get('/', 'UsersController@index');
            // 新增
            Route::post('/', 'UsersController@store');
            // 修改
            Route::put('/{uuid}', 'UsersController@update');
            // 刪除
            Route::delete('/{uuid}', 'UsersController@destroy');
            // 取得單一資料
            Route::get('/{uuid}', 'UsersController@single');
            // 排序
            Route::post('/sort', 'UsersController@sort');
        });
        // 權限管理
        Route::prefix('group')->namespace('Group')->middleware('permission:1002')->group(function () {
            // 列表
            Route::get('/', 'GroupController@index');
            // 新增
            Route::post('/', 'GroupController@store');
            // 修改
            Route::put('/{id}', 'GroupController@update');
            // 刪除
            Route::delete('/{id}', 'GroupController@destroy');
            // 取得單一資料
            Route::get('/{id}', 'GroupController@single');
        });
        /*
        |--------------------------------------------------------------------------
        | 檔案管理
        |--------------------------------------------------------------------------
        */
        // 檔案
        Route::prefix('file')->namespace('File')->middleware('permission:1101')->group(function () {
            // 列表
            Route::get('/', 'FileController@index');
            // 新增
            Route::post('/', 'FileController@store');
            // 修改因為有檔案所以沒辦法使用put
            Route::post('/update/{file}', 'FileController@update');
            // 刪除
            Route::delete('/{file}', 'FileController@destroy');
            // 取得單一資料
            Route::get('/{file}', 'FileController@show');
        });
    });
});
