<?php

namespace Query\User;
use System\Query\RowCacheQuery;

class UserQuery extends RowCacheQuery{

	private $primaryKey = 'uid';//主键在数据库中的命名,行缓存和数据库的交互使用主键

	/**
	 * @Inject("Persistence\User\UserCache")
	 */
	private $cacheLayer;//缓存层	

	/**
	 * @Inject("Persistence\User\UserDb")
	 */
	private $dbLayer;//数据层

	public function __construct(){
		parent::__construct($this->primaryKey,$this->cacheLayer,$this->dbLayer);
	}
}