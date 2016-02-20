<?php
namespace Persistence\User;

use System\Classes\Db;
/**
 * 系统文件数据库操作类 
 *
 */
class UserDb extends Db{
	
	public function __construct(){
		parent::__construct('user');
	}
}

