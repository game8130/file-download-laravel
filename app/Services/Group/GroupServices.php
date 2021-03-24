<?php

namespace App\Services\Group;

use App\Repositories\Group\GroupsRepository;
use App\Repositories\User\UsersRepository;
use App\Repositories\Permission\PermissionRepository;
use Carbon\Carbon;

class GroupServices
{
    private $groupsRepository;
    private $usersRepository;
    private $permissionRepository;

    public function __construct(
        GroupsRepository $groupsRepository,
        UsersRepository $usersRepository,
        PermissionRepository $permissionRepository
    ) {
        $this->groupsRepository = $groupsRepository;
        $this->usersRepository = $usersRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * 權限管理-列表
     * @param array $request
     * @return array
     */
    public function index(array $request = [])
    {
        try {
            $group = $this->groupsRepository->getByWithUser($request);
            foreach ($group as $value) {
                $value['user_count'] = $value['users']->count();
                unset($value['users']);
            }
            return [
                'code'   => config('apiCode.success'),
                'result' => $group,
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 權限管理-新增
     *
     * @param array $request
     * @return array
     */
    public function store(array $request)
    {
        try {
            $group = $this->groupsRepository->store(['name' => $request['name']]);
            return $this->storePermission($request, $group['id']);
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 權限管理-修改
     *
     * @param array $request
     * @return array
     */
    public function update(array $request)
    {
        try {
            $this->permissionRepository->destroyByGroupId($request['id']);
            $this->groupsRepository->update($request['id'], ['name' => $request['name']]);
            return $this->storePermission($request, $request['id']);
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 權限管理-刪除
     *
     * @param array $request
     * @return array
     */
    public function destroy(array $request)
    {
        try {
            $userCount = $this->usersRepository->checkFieldExist('group_id', $request['id'])->count();
            if ($userCount != 0) {
                return [
                    'code'   => config('apiCode.unchangeable'),
                    'error'  => '此群組還有人員無法刪除',
                ];
            }
            return [
                'code'   => config('apiCode.success'),
                'result' => $this->groupsRepository->destroy($request['id']),
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 權限管理-刪除
     *
     * @param array $request
     * @return array
     */
    public function single(array $request)
    {
        try {
            return [
                'code'   => config('apiCode.success'),
                'result' => $this->groupsRepository->find($request['id']),
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @param array $request
     * @param int   $groupId
     * @return array
     */
    public function storePermission($request, $groupId) {
        try {
            $permission = [];
            $insert = false;
            foreach ($request['permissions'] as $value) {
                if (!empty($value)) {
                    $permission[] = [
                        'group_id'   => $groupId,
                        'func_key'   => $value,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ];
                    $insert = true;
                }

            }
            if ($insert) {
                $this->permissionRepository->insertMuti($permission);
            }
            return [
                'code'   => config('apiCode.success'),
                'result' => true,
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 取得功能權限設定資料
     *
     * @return array
     */
    public function getPermission()
    {
        try {
            $permission = [];
            foreach (config('permission.permission') as $value) {
                if ($value['permission'] === true) {
                    $tmpMenu = [];
                    foreach ($value['menu'] as $sub) {
                        if ($sub['permission'] === true) {
                            $tmpAction = [];
                            foreach ($sub['action'] as $action) {
                                $tmpAction[] = [
                                    'func_key' => $action['func_key'],
                                    'name'     => $action['name'],
                                ];
                            }
                            $tmpMenu[] = [
                                'func_key' => $sub['func_key'],
                                'name'     => $sub['name'],
                                'action'   => $tmpAction,
                            ];
                        }
                    }
                    $permission[] = [
                        'func_key' => $value['func_key'],
                        'name'     => $value['name'],
                        'menu'     => $tmpMenu,
                    ];
                }
            }
            return [
                'code'   => config('apiCode.success'),
                'result' => $permission,
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * 取得功能權限設定(func_key)
     *
     * @return array
     */
    public function getPermissionFuncKey()
    {
        try {
            $funcKey = [];
            foreach (config('permission.permission') as $value) {
                if ($value['permission'] === true) {
                    foreach ($value['menu'] as $sub) {
                        if ($sub['permission'] === true) {
                            foreach ($sub['action'] as $action) {
                                $funcKey[] = $action['func_key'];
                            }
                            $funcKey[] = $sub['func_key'];
                        }
                    }
                    $funcKey[] = $value['func_key'];
                }
            }
            return [
                'code'   => config('apiCode.success'),
                'result' => $funcKey,
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }
}
