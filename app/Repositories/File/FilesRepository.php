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

    /**
     * 取得下拉式選單資料
     *
     * @return array
     */
    public function dropdown()
    {
        return Files::select('id', 'name')->orderBy('id', 'ASC')->get()->toArray();
    }

    public function list(array $parameters)
    {
        $file = Files::select(['*'])->with('fileUrl');
        return $this->sortByAndItemsPerPage($file, $parameters);
    }

    public function findWithFileUrl($id)
    {
        return Files::with('fileUrl')->find($id);
    }
}
