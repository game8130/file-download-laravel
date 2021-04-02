<?php

namespace App\Services\Group;

use App\Repositories\Group\GroupsRepository;
use App\Repositories\User\UsersRepository;
use App\Repositories\File\FilesRepository;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionFileRepository;
use Carbon\Carbon;

class GroupServices
{
    private $groupsRepository;
    private $usersRepository;
    private $filesRepository;
    private $permissionRepository;
    private $permissionFileRepository;

    public function __construct(
        GroupsRepository $groupsRepository,
        UsersRepository $usersRepository,
        FilesRepository $filesRepository,
        PermissionRepository $permissionRepository,
        PermissionFileRepository $permissionFileRepository
    ) {
        $this->groupsRepository = $groupsRepository;
        $this->usersRepository = $usersRepository;
        $this->filesRepository = $filesRepository;
        $this->permissionRepository = $permissionRepository;
        $this->permissionFileRepository = $permissionFileRepository;
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
            $this->storePermission($request['permissions'], $group['id']);
            return $this->storePermissionFile($request['files'], $group['id']);
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
            $this->permissionRepository->deleteByWhere('group_id', $request['id']);
            $this->storePermission($request['permissions'], $request['id']);
            $this->permissionFileRepository->deleteByWhere('group_id', $request['id']);
            $this->storePermissionFile($request['files'], $request['id']);
            $this->groupsRepository->update($request['id'], ['name' => $request['name']]);
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
            $this->permissionRepository->deleteByWhere('group_id', $request['id']);
            $this->permissionFileRepository->deleteByWhere('group_id', $request['id']);
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
     * 權限管理-取得單一資料
     *
     * @param array $request
     * @return array
     */
    public function single(array $request)
    {
        try {
            return [
                'code'   => config('apiCode.success'),
                'result' => [
                    'group' => $this->groupsRepository->find($request['id']),
                    'permission' => $this->permissionRepository->hasPermissionAll($request['id']),
                    'file' => $this->permissionFileRepository->hasFileAll($request['id']),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @param array $permissions
     * @param int   $groupId
     * @return array
     */
    public function storePermission($permissions, $groupId) {
        try {
            $permission = [];
            $insert = false;
            if(!empty($permissions)) {
                foreach ($permissions as $value) {
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
            $result['permissions'] = $permission;
            $result['files'] = $this->filesRepository->dropdown();
            return [
                'code'   => config('apiCode.success'),
                'result' => $result,
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

    /**
     * 新增檔案權限
     * 
     * @param array $files
     * @param int   $groupId
     * @return array
     */
    public function storePermissionFile($files, $groupId)
    {
        try {
            $permission = [];
            $insert = false;
            if (!empty($files)) {
                foreach($files as $value) {
                    $permission[] = [
                        'group_id'   => $groupId,
                        'file_id'    => $value,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ];
                    $insert = true;
                }
            }
            if ($insert) {
                $this->permissionFileRepository->insertMuti($permission);
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
     * 會員端
     * @param array $request
     * @return array
     */
    public function webIndex(array $request = [])
    {
        try {
            $permissionFile = $this->permissionFileRepository->getByWith($request);
            $collection = collect($permissionFile);
            $sorted = $collection->sort();
            return [
                'code'   => config('apiCode.success'),
                'result' => $sorted->values()->all(),
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }
}
