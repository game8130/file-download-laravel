<?php

namespace App\Entities\Permission;

use App\Entities\FileDownloadModel;

class Permission extends FileDownloadModel
{
    protected $table = 'permissions';
    protected $fillable = ['group_id', 'func_key'];
}
