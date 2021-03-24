<?php

namespace App\Entities\Group;

use App\Entities\FileDownloadModel;
use App\Entities\User\Users;

class Groups extends FileDownloadModel
{
    protected $table = 'groups';
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasOne(Users::class, 'group_id', 'id');
    }
}
