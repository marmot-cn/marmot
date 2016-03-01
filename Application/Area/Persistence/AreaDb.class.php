<?php
namespace Area\Persistence;

use System\Classes\Db;
/**
 * area表缓数据库文件
 * @author chloroplast
 * @version 1.0.20160223
 */
class AreaDb extends Db{
	
	public function __construct(){
		parent::__construct('area');
	}
}
?>