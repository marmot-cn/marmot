<?php
namespace Common\Persistence;

use System\Classes\Db;
/**
 * 系统文件数据库操作类 
 *
 */

class FileDb extends Db{
		
	public function __construct(){
		parent::__construct('file');
	}
}

