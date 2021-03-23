<?php

namespace App\Entities\Permission;

use App\Entities\FileDownloadModel;

class PermissionFile extends FileDownloadModel
{
    protected $table = 'permission_file';
    protected $fillable = ['group_id', 'func_key'];
}
