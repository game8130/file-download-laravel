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
}
