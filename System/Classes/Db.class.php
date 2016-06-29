<?php
//powered by kevin
namespace System\Classes;

use Core;
use System\Interfaces\DbLayer;

/**
 * Db 操作父类
 * @author chloroplast1983
 * @version 1.0.20131007
 */

abstract class Db implements DbLayer
{
    
    /**
     * @var string DB操作表名,不需要添加前缀
     */
    protected $table;

    /**
     * @var string DB表的前缀
     */
    private $tablepre = 'pcore_';
    
    public function __construct(string $table)
    {
        $this->table = $table;
    }
    /**
     * 删除数据操作,但是不提倡物理删除数据
     * @param array|string $wheresqlArr 查询匹配条件
     */
    public function delete($whereSqlArr)
    {
        return Core::$_dbDriver->delete($this->tname($this->table), $whereSqlArr, $bind = "");
    }
    
    /**
     * 插入数据操作,给表里插入一条数据
     * @param array $insertSqlArr 需要插入数据库的数据数组
     * @param bool $returnLastInsertId 是否返回最新插入的id
     */
    public function insert($insertSqlArr, $returnLastInsertId = true)
    {
        $rows = Core::$_dbDriver->insert($this->tname($this->table), $insertSqlArr);
        return $returnLastInsertId ? Core::$_dbDriver->lastInertId() : $rows;
    }
    
    /**
     * 查询数据
     * @param stirng $sql condition 查询条件
     * @param string $select 查询数据
     * @param string $useIndex 强制使用何索引
     */
    public function select(string $sql, string $select = '*', string $useIndex = '')
    {
        $sql = $sql == '' ? '' : ' WHERE ' . $sql;
        $sqlstr = 'SELECT ' . $select . ' FROM ' . $this->tname($this->table) . $useIndex . $sql;
        return Core::$_dbDriver->query($sqlstr);
    }

    /**
     * 更新数据表数据
     * @param array $setSqlArr 需要更新的数据数组
     * @param array | string $wheresqlArr 匹配条件
     */
    public function update(array $setSqlArr, $whereSqlArr)
    {
        return Core::$_dbDriver->update($this->tname($this->table), $setSqlArr, $whereSqlArr);
    }

    /**
     * 为表添加前缀
     */
    private function tname($table)
    {

        return $this->tablepre.$table;
    }
}
