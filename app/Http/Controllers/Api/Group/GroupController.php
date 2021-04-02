<?php

namespace App\Http\Controllers\Api\Group;

use App\Services\Group\GroupServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class GroupController extends Controller {

    private $groupServices;

    public function __construct(GroupServices $groupServices)
    {
        $this->groupServices = $groupServices;
    }

    /**
     * 自訂驗證
     */
    private function validatorExtend() {
        // 權限檢查
        Validator::extend('permissions', function ($attribute, $permissions, $parameters, $validator) {
            $funcKeys = $this->groupServices->getPermissionFuncKey()['result'];
            foreach ($permissions as $permission) {
                if (empty($permission)) {
                    return true;
                }
                if (!in_array($permission, $funcKeys)) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * 權限管理-列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->responseWithJson($request, $this->groupServices->index($request->all()));
    }

    /**
     * 權限管理-新增
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validatorExtend();
        $validator = Validator::make($request->all(), [
            'name'        => 'required|unique:groups,name|max:20',
            'permissions' => 'required|array|permissions',
            'files'       => 'required|array',
            'files.*'     => 'required|distinct|exists:files,id',
        ], ['permissions' => '權限值傳入錯誤']);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->groupServices->store($request->all()));
    }

    /**
     * 權限管理-修改
     *
     * @param Request $request
     * @param int      $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request['id'] = $id;
        $this->validatorExtend();
        $validator = Validator::make($request->all(), [
            'id'          => 'required|exists:groups,id',
            'name'        => 'required|max:20|unique:groups,name,'.$id,
            'permissions' => 'required|array|permissions',
            'files'       => 'required|array',
            'files.*'     => 'required|distinct|exists:files,id',
        ], ['permissions' => '權限值傳入錯誤']);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->groupServices->update($request->all()));
    }

    /**
     * 權限管理-刪除
     *
     * @param Request $request
     * @param int      $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $request['id'] = $id;
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:groups,id',
        ]);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->groupServices->destroy($request->all()));
    }

    /**
     * 權限管理-取得單一資料
     *
     * @param Request $request
     * @param int      $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function single(Request $request, $id)
    {
        $request['id'] = $id;
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:groups,id',
        ]);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->groupServices->single($request->all()));
    }

    /**
     * 取得功能權限設定資料
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPermission(Request $request)
    {
        return $this->responseWithJson($request, $this->groupServices->getPermission());
    }

    /**
     * 會員端
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function webIndex(Request $request)
    {
        return $this->responseWithJson($request, $this->groupServices->webIndex($request->all()));
    }
}
