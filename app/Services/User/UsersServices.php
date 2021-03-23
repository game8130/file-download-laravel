<?php

namespace App\Services\User;

use App\Repositories\User\UsersRepository;
use App\Repositories\Group\GroupsRepository;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Hash;

class UsersServices
{
    protected $JWTAuth;

    private $usersRepository;
    private $groupsRepository;
    private $usersLoginServices;

    public function __construct(
        JWTAuth $JWTAuth,
        UsersRepository $usersRepository,
        GroupsRepository $groupsRepository,
        UsersLoginServices $usersLoginServices

    ) {
        $this->JWTAuth = $JWTAuth;
        $this->usersRepository = $usersRepository;
        $this->groupsRepository = $groupsRepository;
        $this->usersLoginServices = $usersLoginServices;
    }


    /**
     * 登入驗證
     *
     * @param  array  $request
     * @param  string $ip
     * @return array
     */
    public function login(array $request, $ip)
    {
        $user = [];
        // 驗證帳號密碼是否正確
        try {
            if (!$user['token'] = $this->JWTAuth->attempt([
                'account'  => $request['account'],
                'password' => $request['password'],
                'active'   => 1,
            ])) {
                return [
                    'code'  => config('apiCode.invalidCredentials'),
                    'error' => 'invalid credentials',
                ];
            }
        } catch (JWTException $e) {
            return [
                'code'  => config('apiCode.couldNotCreateToken'),
                'error' => 'could not create token',
            ];
        }


        // 取得 token 並更新該人員 token 資訊
        try {
            $user['user'] = $this->JWTAuth->setToken($user['token'])->toUser();
            // 黑名單之前 token
//            $this->JWTAuth->setToken($user['user']['token'])->invalidate();
            $this->usersRepository->update($user['user']->id, ['token' => $user['token']]);
            $this->usersLoginServices->storeLogin(['id' => $user['user']->id, 'account' => $request['account']], $ip);
            return [
                'code'   => config('apiCode.success'),
                'result' => $user,
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 註冊
     *
     * @param array $request
     * @return array
     */
    public function register(array $request)
    {
        try {
            $groupId = $this->groupsRepository->checkFieldExist('name', config('default.GeneralGroupName'));
            $user = $this->usersRepository->store([
                'group_id' => $groupId[0]['id'],
                'account'  => $request['account'],
                'email'    => $request['email'],
                'password' => Hash::make($request['password']),
                'name'     => $request['account'],
                'active'   => 1,
                'token'    => '',
            ]);
            return [
                'code'   => config('apiCode.success'),
                'result' => $user,
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 取得目前帳號詳細資訊(含功能權限)
     *
     * @return array
     */
    public function information()
    {
        try {
            $user = $this->JWTAuth->parseToken()->authenticate();
            $user['permission'] = $this->getPermission($user['group_role_id']);
            return [
                'code'   => config('apiCode.success'),
                'result' => $user,
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 取得該權限的功能權限資訊
     *
     * @param integer  $groupId
     * @return array
     */
    public function getPermission($groupId)
    {
        try {
            $permission = $this->permissionRepository->hasPermissionAll($groupId)->toArray();
            $menu = [];
            foreach (config('permission.permission') as $key => $value) {
                if ($value['permission'] === true) {
                    foreach ($value['menu'] as $sub) {
                        if (in_array($sub['func_key'], $permission)) {
                            $menu[] = $sub['route'];
                        }
                    }
                }
            }
            return $menu;
        } catch (\Exception $e) {
            return [];
        }
    }
}
