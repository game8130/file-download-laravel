<?php

namespace App\Repositories\User;

use App\Entities\User\Users;
use App\Repositories\Repository;

class UsersRepository
{
    use Repository;

    public function __construct()
    {
        $this->setEntity(Users::class);
    }

    /**
     * 取得下拉式選單資料
     *
     * @return array
     */
    public function dropdown()
    {
        return Users::select('id', 'name')->orderBy('id', 'ASC')->get()->toArray();
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function list(array $parameters)
    {
        $user = Users::select(['*'])->with('group');
        return $this->sortByAndItemsPerPage($user, $parameters);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function findWithGroup($id)
    {
        return Users::where('id', $id)->with('group')->first();
    }
}
