<?php

namespace App\Repositories;

use DB;

trait Repository
{
    private $entity;

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * 取得所有資料
     *
     * @param  array  $field [要搜尋的欄位, Ex.['id', 'name']]
     * @return mixed
     */
    public function getAll($field = ['*'])
    {
        return $this->entity::select($field)->get();
    }

    /**
     * 取得多筆資料(用會員ID)
     *
     * @param  integer   $memberId [會員ID]
     * @param  array     $field    [要取得的欄位資料]
     * @return mixed
     */
    public function getByMemberID($memberId, $field = ['*'])
    {
        return $this->entity::select($field)->where('member_id', $memberId)->get();
    }

    /**
     * 依搜尋條件取得資料(whereIn)
     *
     * @param  string  $whereField [搜尋條件欄位名稱]
     * @param  array   $whereValue [搜尋條件資訊]
     * @param  array   $field      [要搜尋的欄位, Ex.['id', 'name']]
     * @return mixed
     */
    public function getByWhereIn($whereField = 'id', $whereValue = [], $field = ['*'])
    {
        return $this->entity::select($field)->whereIn($whereField, $whereValue)->get();
    }

    /**
     * 取得單筆資料
     *
     * @param  integer  $id  [PK]
     * @return mixed
     */
    public function find($id)
    {
        return $this->entity::find($id);
    }

    /**
     * 取得單筆資料(用會員ID)
     *
     * @param  integer   $memberId [會員ID]
     * @param  array     $field    [要取得的欄位資料]
     * @return boolean
     */
    public function findByMemberID($memberId, $field = ['*'])
    {
        return $this->entity::select($field)->where('member_id', $memberId)->first();
    }

    /**
     * 依指定欄位及資料取得資料
     *
     * @param string  $field  [要搜尋的欄位]
     * @param string  $data   [資料]
     * @param integer $id     [排除PK]
     *
     * @return mixed
     */
    public function checkFieldExist($field, $data, $id = 0)
    {
        return $this->entity::where($field, $data)->where('id', '!=', $id)->get();
    }

    /**
     * 新增資料
     *
     * @param  array  $parameters [新增資料陣列]
     * @return mixed
     */
    public function store(array $parameters = [])
    {
        return $this->entity::create($parameters);
    }

    /**
     * 新增多筆資料
     *
     * @param  array  $parameters [新增資料陣列]
     * @return mixed
     */
    public function insertMuti(array $parameters = [])
    {
        return $this->entity::insert($parameters);
    }

    /**
     * 更新單筆資料
     *
     * @param  integer  $id         [PK]
     * @param  array    $parameters [更新資料陣列]
     * @return boolean
     */
    public function update($id, array $parameters = [])
    {
        return $this->entity::find($id)->update($parameters);
    }

    /**
     * 更新多筆資料
     *
     * @param  array    $data       [更新條件資料]
     * @param  array    $parameters [更新資料陣列]
     * @param  string   $field      [更新條件名稱]
     * @return boolean
     */
    public function updateMuti($data, array $parameters = [], string $field = 'id')
    {
        return $this->entity::whereIn($field, $data)->update($parameters);
    }

    /**
     * 更新單筆資料(用會員ID)
     *
     * @param  integer  $memberId   [會員ID]
     * @param  array    $parameters [更新資料陣列]
     * @return boolean
     */
    public function updateByMemberID($memberId, array $parameters = [])
    {
        return $this->entity::where('member_id', $memberId)->update($parameters);
    }

    /**
     * 刪除資料
     *
     * @param  integer  $id  [PK]
     * @return boolean
     */
    public function destroy($id)
    {
        return $this->entity::destroy($id);
    }

    /**
     * 依條件刪除資料
     *
     * @param  string  $field  [欄位名稱]
     * @param  string  $val    [欄位值]
     * @return boolean
     */
    public function deleteByWhere($field, $val)
    {
        return $this->entity::where($field, $val)->delete();
    }

    /**
     * 刪除資料[WhereIn 方式]
     *
     * @param  array   $data   [條件值]
     * @param  string  $field  [條件欄位名稱]
     * @return boolean
     */
    public function destroyByWhereIn($data, $field = 'id')
    {
        return $this->entity::whereIn($field, $data)->delete();
    }

    /**
     * 資料新增，存在則更新
     *
     * @param  array  $where      [條件]
     * @param  array  $parameters [更新資料參數]
     * @return mixed
     */
    public function updateOrCreate(array $where, array $parameters)
    {
        return $this->entity::updateOrCreate($where, $parameters);
    }

    /**
     * 批次更新 or 新增資料
     *
     * @param  string $table      [要執行的table名稱]
     * @param  string $field      [欄位]
     * @param  string $sql        [資料]
     * @param  string $duplicate  [資料存在要更新的欄位資訊]
     * @return mixed
     */
    public function updateOrCreateMulti(string $table, string $field, string $sql, string $duplicate)
    {
        DB::connection($this->entity::getNowConnection())->statement(" ALTER TABLE `" . $table . "` AUTO_INCREMENT = 1");
        return DB::connection($this->entity::getNowConnection())
            ->statement("INSERT INTO `" . $table . "` (" . $field . ") VALUES " . $sql . " ON DUPLICATE KEY UPDATE ". $duplicate ." ;");
    }

    /*
    |--------------------------------------------------------------------------
    | 不用讀寫分離
    |--------------------------------------------------------------------------
    |
    */

    /**
     * 取得單筆資料
     *
     * @param  integer  $id  [PK]
     * @return mixed
     */
    public function findWriteConnect($id)
    {
        return $this->entity::onWriteConnection()->find($id);
    }

    /**
     * 查詢帶有多個條件式
     *
     * @param  array  $params  [$key => $value]
     * @param  bool   $toArray
     * @return mixed
     */
    public function whereMuti($params, $toArray = false)
    {
        $entity = $this->entity::select('*');
        foreach ($params as $key => $value) {
            $entity->where($key, $value);
        }
        $response = $entity->get();
        return ($toArray) ? $response->toArray() : $response;
    }
}
