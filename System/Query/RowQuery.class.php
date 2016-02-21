<?php
namespace System\Query;

/**
 * 对于一些不需要cache层数据的调用
 */
abstract class RowQuery {

	private $primaryKey;//主键在数据库中的命名,行缓存和数据库的交互使用主键
	private $dbLayer;//数据层

	public function __construct(string $primaryKey,Interfaces\CacheLayer $cacheLayer,Interfaces\DbLayer $dbLayer){
		$this->primaryKey = $primaryKey;
		$this->dbLayer = $dbLayer;
	}

	public function __destruct(){
		unset($this->primaryKey);
		unset($this->dbLayer);
	}

	/**
	 * @param int $id,主键id
	 */
	public function getOne($id){

		$mysqlData = $this->dbLayer->select($this->primaryKey.'='.$id,'*');

		//如果数据为空,返回false
		if(empty($mysqlData)){
			return false;
		}
		//返回数据
		return $mysqlData;
	}

	/**
	 * 批量获取缓存
	 */
	public function getList($ids){

		if(empty($ids) || !is_array($ids)){
			return false;
		}

		$resArray = $this->dbLayer->select($this->primaryKey.' in (' . implode(',', $ids) . ')', '*');
		return $resArray;
	}	
}