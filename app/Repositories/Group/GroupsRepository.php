<?php

namespace App\Repositories\Group;

use App\Entities\Group\Groups;
use App\Repositories\Repository;

class GroupsRepository
{
    use Repository;

    public function __construct()
    {
        $this->setEntity(Groups::class);
    }

    /**
     * 取得下拉式選單資料
     *
     * @return array
     */
    public function dropdown()
    {
        return Groups::select('id', 'name')->orderBy('id', 'ASC')->get()->toArray();
    }

    /**
     * @param array $parameters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getByWithUser(array $parameters)
    {
        $group = Groups::with('users');
        return $this->sortByAndItemsPerPage($group, $parameters);
    }
}
