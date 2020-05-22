<?php

namespace App\Entities\User;

use App\Entities\BoserpModel;

class UsersLogin extends BoserpModel
{
    protected $table = 'users_login';
    protected $fillable = [
        'user_id', 'user_account', 'login_ip', 'device', 'device_info', 'area', 'status'
    ];
    protected $casts    = [
        'device_info' => 'array',
    ];
}
