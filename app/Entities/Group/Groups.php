<?php

namespace App\Entities\Group;

use App\Entities\FileDownloadModel;
use App\Entities\User\Users;

class Groups extends FileDownloadModel
{
    protected $table = 'groups';
    protected $fillable = ['name'];

    /**
     * 使用者
     */
    public function users()
    {
        return $this->hasMany(Users::class, 'group_id', 'id');
    }
}
