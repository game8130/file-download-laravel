<?php

namespace App\Http\Controllers\Api\Dropdown;

use App\Services\Dropdown\DropdownServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class DropdownController extends Controller {

    private $method = ['group', 'user', 'active'];
    private $dropdownServices;

    public function __construct(DropdownServices $dropdownServices)
    {
        $this->dropdownServices = $dropdownServices;
    }

    /**
     * 下拉式選單
     *
     * @param Request $request
     * @param string   $method
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $method)
    {
        $request['method'] = $method;
        $param = [];
        $validator = Validator::make($request->all(), [
            'method'     => 'required|in:' . implode(',', $this->method),
        ]);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        $param['all'] = $request->input('all', '');
        $param['id'] = $request->input('id', '');
        return $this->responseWithJson($request, $this->dropdownServices->dropdown($method, $param));
    }
}
