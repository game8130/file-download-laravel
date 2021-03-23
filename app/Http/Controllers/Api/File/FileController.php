<?php

namespace App\Http\Controllers\Api\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\File\FileServices;

class FileController extends Controller
{
    private $fileServices;

    public function __construct(FileServices $fileServices)
    {
        $this->fileServices = $fileServices;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->responseWithJson($request, $this->fileServices->index($request->all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:files,name|max:100',
            'file' => 'required|file|mimes:ppt,pptx,doc,docx,pdf,xls,xlsx|max:204800',
        ]);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->fileServices->store($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  $file
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $file)
    {
        $request['id'] = $file;
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:files,id',
        ]);
        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->fileServices->show($request->all()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $file)
    {
        $request['id'] = $file;
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:files,id',
            'name' => 'max:100|unique:files,name,' . $request['id'],
            'file' => 'file|mimes:ppt,pptx,doc,docx,pdf,xls,xlsx|max:204800',
        ]);

        if ($validator->fails()) {
            return $this->apiValidateFail($request, $validator);
        }
        return $this->responseWithJson($request, $this->fileServices->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        //
    }
}
