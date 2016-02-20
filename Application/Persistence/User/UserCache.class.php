<?php
namespace Persistence\User;

use System\Classes\Cache;
/**
 * 用户表关系缓存
 */
class UserCache extends Cache{

	public function __construct(){
		parent::__construct('user');
	}
}

