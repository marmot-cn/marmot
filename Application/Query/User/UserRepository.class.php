<?php

namespace Query\User;
use System\Query\RowCacheQuery;
use Persistence\User;
use Core;

class UserRepository extends RowCacheQuery{

	private $primaryKey = 'uid';//主键在数据库中的命名,行缓存和数据库的交互使用主键

	
	private $cacheLayer;//缓存层	
	private $dbLayer;//数据层

	public function __construct(User\UserCache $cacheLayer,User\UserDb $dbLayer){
		$this->cacheLayer = $cacheLayer;
		$this->dbLayer = $dbLayer;
		parent::__construct($this->primaryKey,$this->cacheLayer,$this->dbLayer);
	}

	//这里封装其他用户方法,比如根据其他一些条件搜索用户之类的功能,不包含在RowCache内的

	public function getUserCount(){
		return Core::$_container->call(['Query\User\FragmentCache\UserFragmentFactory','createFragment'],['type'=>'count','method'=>'get']);
	}
}