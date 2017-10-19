<?php
//powered by kevin
namespace System\Classes;

use Marmot\Core;
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
    protected $table = '';

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
        return Core::$dbDriver->delete($this->tname(), $whereSqlArr);
    }
    
    /**
     * 插入数据操作,给表里插入一条数据
     * @param array $insertSqlArr 需要插入数据库的数据数组
     */
    public function insert($insertSqlArr, $returnLastInsertId = true) : int
    {
        $rows = Core::$dbDriver->insert($this->tname(), $insertSqlArr);
        if (!$rows) {
            return false;
        }
        
        return $returnLastInsertId ? Core::$dbDriver->lastInertId() : $rows;
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

        $sqlstr = 'SELECT ' . $select . ' FROM ' . $this->tname() . $useIndex . $sql;
        return Core::$dbDriver->query($sqlstr);
    }

    /**
     * 更新数据表数据
     * @param array $setSqlArr 需要更新的数据数组
     * @param array | string $wheresqlArr 匹配条件
     */
    public function update(array $setSqlArr, $whereSqlArr) : bool
    {
        return Core::$dbDriver->update($this->tname(), $setSqlArr, $whereSqlArr);
    }

    /**
     * 添加联表查询功能
     *
     */
    public function join(
        DbLayer $dbLayer,
        string $joinCondition,
        string $sql,
        string $select = '*',
        string $joinDirection = 'I'
    ) {

        $sql = $sql == '' ? '' : ' WHERE ' . $sql;

        $sqlstr = 'SELECT ' . $select . ' FROM ' . $this->tname();

        if ($joinDirection == 'I') {
            $sqlstr .= ' INNER JOIN ';
        } elseif ($joinDirection == 'L') {
            $sqlstr .= ' LEFT JOIN ';
        } elseif ($joinDirection == 'R') {
            $sqlstr .= ' RIGHT JOIN ';
        }

        $sqlstr .= $dbLayer->tname().' ON '.$joinCondition.$sql;
 
        return Core::$dbDriver->query($sqlstr);
    }

    /**
     * 为表添加前缀
     */
    public function tname() : string
    {

        return $this->tablepre.$this->table;
    }
}
