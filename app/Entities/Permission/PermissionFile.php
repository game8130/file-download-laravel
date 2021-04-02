<?php

namespace App\Entities\Permission;

use App\Entities\FileDownloadModel;
use App\Entities\File\Files;

class PermissionFile extends FileDownloadModel
{
    protected $table = 'permission_file';
    protected $fillable = ['group_id', 'file_id'];

    /**
     * 使用者
     */
    public function files()
    {
        return $this->hasOne(Files::class, 'id', 'file_id');
    }
}
