<?php

namespace App\Repositories\File;

use App\Entities\File\Files;
use App\Repositories\Repository;

class FilesRepository
{
    use Repository;

    public function __construct()
    {
        $this->setEntity(Files::class);
    }

    public function list(array $parameters)
    {
        $file = Files::select(['*'])->with('fileUrl');
        return $this->sortByAndItemsPerPage($file, $parameters);
    }
}
