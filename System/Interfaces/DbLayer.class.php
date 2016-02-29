<?php
namespace System\Interfaces;
/**
 * DB层,用于接偶所有使用缓存的"具体"
 * 
 * @codeCoverageIgnore
 * 
 * @author chloroplast
 * @version 1.0: 20160222
 */
interface DbLayer {

	//删除
	function delete($whereSqlArr);

	//插入
	function insert($insertSqlArr,$returnLastInsertId=true);

	//查询
	function select(string $sql);

	//更新
	function update(array $setSqlArr,$whereSqlArr);
}