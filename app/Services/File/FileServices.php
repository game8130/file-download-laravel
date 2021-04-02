<?php

namespace App\Services\File;

use App\Repositories\File\FilesRepository;
use App\Repositories\File\FileUrlRepository;
use DB;
use Illuminate\Support\Facades\Storage;

class FileServices
{
    private $filesRepository;
    private $fileUrlRepository;

    public function __construct(
        FilesRepository $filesRepository,
        FileUrlRepository $fileUrlRepository
    ) {
        $this->filesRepository = $filesRepository;
        $this->fileUrlRepository = $fileUrlRepository;
    }

    /**
     * @param array $request
     * @return array
     */
    public function index(array $request)
    {
        try {
            return [
                'code'   => config('apiCode.success'),
                'result' => $this->filesRepository->list($request),
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @param array $request
     * @return array
     */
    public function store(array $request)
    {
        try {
            $result = DB::connection('mysql')->transaction(function () use ($request) {               
                $storagePath = Storage::put('/public/file', $request['file']);
                $fileName = 'file' . '/' . basename($storagePath);
                $files = $this->filesRepository->store(['name' => $request['name']]);
                $fileUrl = $this->fileUrlRepository->store([
                    'files_id' => $files['id'],
                    'url' => $fileName
                ]);
                $this->filesRepository->update($files['id'], ['file_url_id' => $fileUrl['id']]);
                return true;
            });
            return [
                'code'   => config('apiCode.success'),
                'result' => $result,
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @param array $request
     * @return array
     */
    public function update(array $request)
    {
        try {
            $result = DB::connection('mysql')->transaction(function () use ($request) {
                $update = [];
                if(isset($request['file'])) {
                    $storagePath = Storage::put('/public/file', $request['file']);
                    $fileName = 'file' . '/' . basename($storagePath);
                    $fileUrl = $this->fileUrlRepository->store([
                        'files_id' => $request['id'],
                        'url' => $fileName
                    ]);
                    $update['file_url_id'] = $fileUrl['id'];
                }
                if(isset($request['name'])) {
                    $update['name'] = $request['name'];
                }                             
                if(!empty($update)) {
                    $update['version'] = DB::raw('version+1');
                    $this->filesRepository->update($request['id'], $update);
                }
                return true;
            });
            return [
                'code'   => config('apiCode.success'),
                'result' => $result,
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @param array $request
     * @return array
     */
    public function show(array $request)
    {
        try {
            $files = $this->filesRepository->findWithFileUrl($request['id']);
            $name = $files->name . '.' . pathinfo($files->fileUrl->url, PATHINFO_EXTENSION);
            $files['new_name'] = $name;
            return [
                'code'   => config('apiCode.success'),
                'result' => $files,
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @param array $request
     * @return array
     */
    public function downloadFile(array $request)
    {
        try {
            $file = $this->filesRepository->findWithFileUrl($request['id']);
            $this->fileUrlRepository->update($file->file_url_id, ['count' => $file->fileUrl->count+1]);
            
            return [
                'code'   => config('apiCode.success'),
                'result' => [
                    'name' => $file->name,
                    'url'  => $file->fileUrl->url,
                ],
            ];
        } catch (\Exception $e) {
            return [
                'code'  => $e->getCode() ?? config('apiCode.notAPICode'),
                'error' => $e->getMessage(),
            ];
        }
    }
}
