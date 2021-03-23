<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use App\Repositories\Permission\PermissionRepository;

class Permission
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * 檢查功能權限
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string    $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $permission = explode(".", $permission);
        $user = JWTAuth::parseToken()->authenticate();
        foreach ($permission as $key => $value) {
            if ($this->permissionRepository->hasPermission($user['group_id'], $value)) {
                return $next($request);
            }
        }
        return response('Forbidden.', 403);
    }
}
