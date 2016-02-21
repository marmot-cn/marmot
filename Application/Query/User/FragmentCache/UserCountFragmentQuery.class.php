<?php
/**
 * 一个计算用户总数的片段缓存用于代码展示
 */
namespace Query\User\FragmentCache;
use System\Query\FragmentCacheQuery;

class UserCountFragmentQuery extends FragmentCacheQuery {

	private $fragmentKey = 'userCount';//片段缓存key名
	
	private $cacheLayer;//缓存层	

	private $dbLayer;//数据层

	/**
	 * @Inject({"Persistence\User\UserCache", "Persistence\User\UserDb"})
	 */
	public function __construct($cacheLayer,$dbLayer){
		$this->cacheLayer = $cacheLayer;
		$this->dbLayer = $dbLayer;
		parent::__construct($this->fragmentKey,$cacheLayer,$dbLayer);
	}

	public function refresh(){
		//查询count总数
		$result = $this->dbLayer->select('','COUNT(*) as count');
		
		if(!$result){
			return false;
		}
		$count = $result[0]['count'];

		//这里还可以拼接其他数据

		$this->cacheLayer->save($this->fragmentKey,$count);
		
		return $count;
	}
}