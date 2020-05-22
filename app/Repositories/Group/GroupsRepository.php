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
}
