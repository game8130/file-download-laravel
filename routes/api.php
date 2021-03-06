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
        /*
        |--------------------------------------------------------------------------
        | 會員端資料
        |--------------------------------------------------------------------------
        */
        Route::prefix('/web')->group(function () {
            Route::prefix('/group')->namespace('Group')->group(function () {
                // 檔案權限
                Route::get('/', 'GroupController@webIndex');
            });
            Route::prefix('/file')->namespace('File')->group(function () {
                // 取得檔案名稱
                Route::get('/{file}', 'FileController@show');
                // 檔案權限
                Route::post('/download', 'FileController@downloadFile')->middleware('permission.file');
            });
        });
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
            Route::put('/{id}', 'UsersController@update');
            // 刪除
            Route::delete('/{id}', 'UsersController@destroy');
            // 取得單一資料
            Route::get('/{id}', 'UsersController@single');
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
            // 權限資訊
            Route::get('/permission', 'GroupController@getPermission');
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

        /*
        |--------------------------------------------------------------------------
        | 系統相關(已登入)
        |--------------------------------------------------------------------------
        */
        // 下拉式選單
        Route::prefix('dropdown')->namespace('Dropdown')->group(function () {
            // 通用設定檔
            Route::get('/{method}', 'DropdownController@index');
        });
    });
});
