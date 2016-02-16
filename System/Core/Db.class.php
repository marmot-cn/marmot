<?php
//powered by kevin
namespace System\Core;
use Core;
/**
 * Db 操作父类
 * @author chloroplast1983
 * @version 1.0.20131007
 */

class Db {
	
	protected static $table = '';
	
	/**
	 * 删除数据操作,但是不提倡物理删除数据
	 * @param array $wheresqlArr 查询匹配条件
	 */
	public static function delete($wheresqlArr) {
		return Core::$_dbDriver->delete(static::$table, $whereSql, $bind="");
	}
	
	/**
	 * 插入数据操作,给表里插入一条数据
	 * @param array $insertSqlArr 需要插入数据库的数据数组
	 */
	public static function insert($insertSqlArr){	
		return Core::$_dbDriver->insert(static::$table, $insertSqlArr);
	}
	
	/**
	 * 查询数据
	 * @param string $useIndex 强制使用何索引
	 */
	public static function select($sql, $select) {
		// return Core::$_dbDriver->fetch_all(static::$table,$sql, $select,$bind='');
	}
	
	/**
	 * 更新数据表数据
	 * @param array $setSqlArr 需要更新的数据数组
	 * @param array $wheresqlArr 匹配条件
	 */
	public static function update($setSqlArr,$wheresqlArr) {
		return Core::$_dbDriver->update(static::$table, $setSqlArr, $whereSql);
	}
}
?>