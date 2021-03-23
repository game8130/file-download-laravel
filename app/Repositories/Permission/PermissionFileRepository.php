<?php

namespace App\Repositories\Permission;

use App\Entities\Permission\PermissionFile;
use App\Repositories\Repository;

class PermissionFileRepository
{
    use Repository;

    public function __construct()
    {
        $this->setEntity(PermissionFile::class);
    }
}
