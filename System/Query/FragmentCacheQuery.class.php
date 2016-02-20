<?php
/**
 * Query层的片段存处理,需要处理页面中的一个片段.
 * 
 */
namespace System\Query;
use System\Classes;
use System\Interfaces;

abstract class FragmentCacheQuery {

	private $fragmentKey;//片段缓存key名

	private $cacheLayer;//缓存层

	private $dbLayer;//数据层

	public function __construct(string $fragmentKey,Interfaces\CacheLayer $cacheLayer,Interfaces\DbLayer $dbLayer){
		$this->fragmentKey = $fragmentKey;
		$this->cacheLayer = $cacheLayer;
		$this->dbLayer = $dbLayer;
	}

	public function __destruct(){
		unset($this->fragmentKey);
		unset($this->cacheLayer);
		unset($this->dbLayer);
	}

	/**
	 * 获取片段,如果缓存失效则必须重新更新该片段缓存
	 */
	public function get(){

		//从缓存获取数据
		$cacheData = $this->cacheLayer->get($this->fragmentKey);
		if($cacheData){
			return $cacheData;
		}

		$cacheData = $this->refresh();
		if(!$cacheData){
			return false;
		}

		return $cacheData;
	}
	/**
	 * 更新缓存片段
	 */
	abstract function refresh();
	/**
	 * 删除片段缓存
	 */
	public function del(){
		$this->cacheLayer->del($this->fragmentKey);
	}
}

?>