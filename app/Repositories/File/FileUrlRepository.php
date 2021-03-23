<?php

namespace App\Repositories\File;

use App\Entities\File\FileUrl;
use App\Repositories\Repository;

class FileUrlRepository
{
    use Repository;

    public function __construct()
    {
        $this->setEntity(FileUrl::class);
    }
}
