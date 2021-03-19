<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\User\UsersServices;

class UsersController extends Controller
{
    private $usersServices;

    public function __construct(UsersServices $usersServices)
    {
        $this->usersServices = $usersServices;
    }

    /**
     * 登入驗證
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account'  => 'required|regex:/^[A-Za-z0-9]+$/|alpha_num|between:4,20',
            'password' => 'required|alpha_num|between:8,12',
        ]);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->usersServices->login($request->all(), $request->ip()));
    }

    /**
     * 註冊
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account'  => 'required|unique:users,account|regex:/^[A-Za-z0-9]+$/|alpha_num|between:4,20',
            'password' => 'required|alpha_num|between:8,12|confirmed',
            'email'    => 'required|unique:users,email|email',
        ]);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->usersServices->register($request->all()));
    }
}
