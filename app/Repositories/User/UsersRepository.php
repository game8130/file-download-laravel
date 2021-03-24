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
        return Users::find($id)->with('group')->first();
    }
}
