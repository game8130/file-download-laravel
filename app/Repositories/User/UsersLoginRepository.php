<?php

namespace App\Repositories\User;

use App\Entities\User\UsersLogin;
use App\Repositories\Repository;

class UsersLoginRepository
{
    use Repository;

    public function __construct()
    {
        $this->setEntity(UsersLogin::class);
    }

}
