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

    /**
     * @param integer $groupId
     * @return mixed
     */
    public function hasFileAll($groupId)
    {
        return PermissionFile::where('group_id', $groupId)->pluck('file_id');
    }

    public function getByWith($parameters)
    {
        $file = PermissionFile::where('group_id', $parameters['jwt']['group_id'])->with([
            'files' => function ($query) {
                $query->where('status', 1)->with('fileUrl');
            }]);

        return $this->sortByAndItemsPerPage($file, $parameters)->whereNotNull('files');

    }

    public function hasPermissionFile($groupId, $id)
    {
        return PermissionFile::where('group_id', $groupId)->where('file_id', $id)->first();
    }
}
