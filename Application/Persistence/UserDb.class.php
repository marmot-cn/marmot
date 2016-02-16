<?php
namespace Persistence;

use System\Core;
/**
 * 系统文件数据库操作类 
 *
 */

class UserDb extends Core\Db{
	
	protected static $table = 'user';
}

