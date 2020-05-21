<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

abstract class BoserpModel extends Model
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
}
