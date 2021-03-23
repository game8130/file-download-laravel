<?php

namespace App\Entities\File;

use App\Entities\FileDownloadModel;

class Files extends FileDownloadModel
{
    protected $table = 'files';
    protected $fillable = ['file_url_id', 'name', 'version', 'status'];

    public function fileUrl()
    {
        return $this->hasOne(FileUrl::class, 'files_id', 'id');
    }
}
