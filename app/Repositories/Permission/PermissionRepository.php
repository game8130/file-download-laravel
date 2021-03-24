<?php

namespace App\Repositories\Permission;

use App\Entities\Permission\Permission;
use App\Repositories\Repository;

class PermissionRepository
{
    use Repository;

    public function __construct()
    {
        $this->setEntity(Permission::class);
    }

    /**
     * 用 角色ID 及 功能 funckey 取得單筆資料
     *
     * @param integer $groupId
     * @param integer $funcKey
     * @return mixed
     */
    public function hasPermission($groupId, $funcKey)
    {
        return Permission::where('group_id', $groupId)->where('func_key', $funcKey)->first();
    }

    /**
     * 用 角色ID 取得 funckey 全部資料
     *
     * @param integer $groupId
     * @return mixed
     */
    public function hasPermissionAll($groupId)
    {
        return Permission::where('group_id', $groupId)->pluck('func_key');
    }

    /**
     * 刪除該權限ID 底下的功能權限資料
     *
     * @param  integer $groupId
     * @return mixed
     */
    public function destroyByGroupId($groupId)
    {
        return Permission::where('group_id', $groupId)->delete();
    }
}
