<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\Permission\PermissionFileRepository;

class PermissionFile
{
    protected $permissionFileRepository;

    public function __construct(PermissionFileRepository $permissionFileRepository)
    {
        $this->permissionFileRepository = $permissionFileRepository;
    }

    /**
     * 檢查功能權限
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!empty($request['id']) &&
            $this->permissionFileRepository->hasPermissionFile($request['jwt']['group_id'], $request['id'])) {
            return $next($request);
        }
        return response('Forbidden.', 403);
    }
}
