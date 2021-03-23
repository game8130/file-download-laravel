<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

abstract class FileDownloadModel extends Model
{
    protected $connection = 'mysql';

    /**
     * 取得對應會員資訊
     *
     * @param  object  $query
     * @param  integer $memberID
     * @return mixed
     */
    public function scopeMember($query, $memberID)
    {
        return $query->where('member_id', $memberID);
    }

    /**
     * 取得對應狀態資訊
     *
     * @param  object  $query
     * @param  integer $active
     * @return mixed
     */
    public function scopeActive($query, $active = 1)
    {
        return $query->where('active', $active);
    }

    /**
     * 取得該 entity 資料表名稱
     *
     * @return string
     */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    /**
     * 格式化上傳時間
     */
    public function getUpdatedAtAttribute($value)
    {
        return $value ? date("Y-m-d H:i:s", strtotime($value)) : '';
    }

    /**
     * 格式化創建時間
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? date("Y-m-d H:i:s", strtotime($value)) : '';
    }
}
