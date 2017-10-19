<?php
namespace System\Query;

use System\Classes;
use System\Interfaces;

/**
 * RowQuery文件,abstract抽象类.所有针对数据库行处理不需要缓存的类需要继承该类.
 *
 * RowQuery 和 RowCacheQuery不同的是不带缓存,针对一些余额,订单等严谨的数据使用.
 *
 * @author chloroplast
 * @version 1.0.0: 20160224
 */
abstract class RowQuery
{

    use RowQueryFindable;
    
    private $primaryKey;//主键在数据库中的命名,行缓存和数据库的交互使用主键
    private $dbLayer;//数据层

    public function __construct(string $primaryKey, Interfaces\DbLayer $dbLayer)
    {
        $this->primaryKey = $primaryKey;
        $this->dbLayer = $dbLayer;
    }

    public function __destruct()
    {
        unset($this->primaryKey);
        unset($this->dbLayer);
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
    
    /**
     * @param array $data 添加数据
     */
    public function add(array $data, $lasetInsertId = true)
    {
        $result = $this->dbLayer->insert($data, $lasetInsertId);

        if (!$result) {
            return false;
        }
        return $result;
    }

    /**
     * @param array | string $data 更新数据
     * @param array $condition 更新条件 | 默认为主键
     */
    public function update($data, array $condition)
    {
        $row = $this->dbLayer->update($data, $condition);
        if (!$row) {
            return false;
        }
        return true;
    }
    
    /**
     * @param int $id,主键id
     */
    public function getOne($id)
    {
        $mysqlData = $this->dbLayer->select($this->primaryKey.'='.$id, '*');
        //如果数据为空,返回false
        if (empty($mysqlData) || !isset($mysqlData[0])) {
            return false;
        }
        $mysqlData = $mysqlData[0];

        //返回数据
        return $mysqlData;
    }

    /**
     * 批量获取缓存
     */
    public function getList($ids)
    {

        if (empty($ids) || !is_array($ids)) {
            return false;
        }

        $resArray = $this->dbLayer->select($this->primaryKey.' in (' . implode(',', $ids) . ')', '*');
        return $resArray;
    }
}
