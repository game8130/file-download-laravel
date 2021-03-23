<?php

namespace App\Entities\Group;

use App\Entities\FileDownloadModel;

class Groups extends FileDownloadModel
{
    protected $table = 'groups';
    protected $fillable = ['name'];
}
