<?php

namespace App\Services\Group;

use App\Repositories\Group\GroupsRepository;
use App\Repositories\User\UsersRepository;

class GroupServices
{
    private $groupsRepository;
    private $usersRepository;

    public function __construct(
        GroupsRepository $groupsRepository,
        UsersRepository $usersRepository
    ) {
        $this->groupsRepository = $groupsRepository;
        $this->usersRepository = $usersRepository;
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
            return [
                'code'   => config('apiCode.success'),
                'result' => $this->groupsRepository->store([
                    'name' => $request['name'],
                ]),
            ];
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
            return [
                'code'   => config('apiCode.success'),
                'result' => $this->groupsRepository->update($request['id'], [
                    'name' => $request['name'],
                ]),
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
}
