<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\User\UsersServices;
use Illuminate\Validation\Rule;

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
            'account'  => 'required|regex:/^[A-Za-z0-9]+$/|alpha_num|between:3,20',
            'password' => 'required|alpha_dash|between:6,20',
            'captcha'  => 'required|captcha_api:'. $request['key'],
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
            'account'  => 'required|unique:users,account|regex:/^[A-Za-z0-9]+$/|alpha_num|between:3,20',
            'password' => 'required|alpha_num|between:6,20|confirmed',
            'email'    => 'required|unique:users,email|email',
        ]);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->usersServices->register($request->all()));
    }

    /**
     * 登入檢查
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function information(Request $request)
    {
        return $this->responseWithJson($request, $this->usersServices->information($request->all()));
    }

    /**
     * 系統登出
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        return $this->responseWithJson($request, $this->usersServices->logout($request->ip()));
    }

    /**
     * 人員管理-列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->responseWithJson($request, $this->usersServices->index($request->all()));
    }

    /**
     * 人員管理-新增
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account'       => 'required|unique:users,account|between:3,20',
            'email'         => 'required|unique:users,email|email',
            'name'          => 'required|max:20',
            'group_id'      => 'required|exists:groups,id',
        ]);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->usersServices->store($request->all()));
    }

    /**
     * 人員管理-修改
     *
     * @param Request $request
     * @param string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request['id'] = $id;
        $validator = Validator::make($request->all(), [
            'id'            => 'required|exists:users,id',
            'group_id'      => 'required|exists:groups,id',
            'active'        => 'in:1,2',
            'password'      => 'alpha_dash|between:6,20|confirmed|nullable',
            'name'          => 'max:20',
            'email'         => [
                'email',
                Rule::unique('users', 'email')->ignore($id, 'id')
            ],
        ]);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->usersServices->update($request->all()));
    }

    /**
     * 人員管理-刪除
     *
     * @param Request $request
     * @param string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $request['id'] = $id;
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->usersServices->destroy($request->all()));
    }

    /**
     * 人員管理-取得單一資料
     *
     * @param Request $request
     * @param string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function single(Request $request, $id)
    {
        $request['id'] = $id;
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->usersServices->single($request->all()));
    }
}
