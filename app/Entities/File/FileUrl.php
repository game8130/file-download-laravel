<?php

namespace App\Entities\File;

use App\Entities\FileDownloadModel;

class FileUrl extends FileDownloadModel
{
    protected $table = 'file_url';
    protected $fillable = ['files_id', 'url', 'count'];

    public function files()
    {
        return $this->belongsTo(FileUrl::class, 'files_id', 'id');
    }
}
