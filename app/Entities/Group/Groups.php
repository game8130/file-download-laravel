<?php

namespace App\Entities\Group;

use App\Entities\BoserpModel;

class Groups extends BoserpModel
{
    protected $table = 'groups';
    protected $fillable = ['name'];
}
