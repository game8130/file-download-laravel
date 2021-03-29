<?php

namespace App\Services\Dropdown;

use App\Repositories\Group\GroupsRepository;
use App\Repositories\User\UsersRepository;
use JWTAuth;

class DropdownServices
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
     * 下拉式選單
     *
     * @param  string  $method
     * @param  array   $param
     * @return array
     */
    public function dropdown($method, $param = [])
    {
        try {
            $dropdown = [];
            switch ($method) {
                case 'group': // 群組
                    $dropdown = $this->groupsRepository->dropdown();
                    if ($param['all'] !== 'hide') {
                        $dropdown = array_merge([['id' => 0, 'name' => '全部']], $dropdown);
                    }
                    break;
                case 'user': // 人員
                    $dropdown = $this->usersRepository->dropdown($param);
                    break;
                case 'active': // 狀態選項
                    $active = config('dropdown.status');
                    if ($param['all'] == 'hide') {
                        unset($active[0]);
                    }
                    foreach ($active as $key => $value) {
                        $dropdown[] = [
                            'id'   => $key,
                            'name' => $value
                        ];
                    }
                    break;
            }
            return [
                'code'   => config('apiCode.success'),
                'result' => $dropdown,
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }
}
