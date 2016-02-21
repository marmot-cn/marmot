<?php
/**
 * Query层的行缓存处理,一张需要行缓存的数据表对应一个RowCacheQuer层,主要实现2个方法:
 * 1. getOne($id) 获取一条记录,需要先判断缓存中是否存在,不存在则从数据获取,存入缓存
 * 2. getList(array $ids) 获取多条记录,需要先判断缓存中是否存在,如果有不存在的,则把不存在id放入数据中查询
 */
namespace System\Query;
use System\Classes;
use System\Interfaces;

abstract class RowCacheQuery {

	private $primaryKey;//主键在数据库中的命名,行缓存和数据库的交互使用主键

	private $cacheLayer;//缓存层

	private $dbLayer;//数据层

	public function __construct(string $primaryKey,Interfaces\CacheLayer $cacheLayer,Interfaces\DbLayer $dbLayer){
		$this->primaryKey = $primaryKey;
		$this->cacheLayer = $cacheLayer;
		$this->dbLayer = $dbLayer;
	}

	public function __destruct(){
		unset($this->primaryKey);
		unset($this->cacheLayer);
		unset($this->dbLayer);
	}
	/**
	 * @param int $id,主键id
	 */
	public function getOne($id){

		//查询缓存中是否有数据,根据id
		$cacheData = $this->cacheLayer->get($id);
		//如果有数据,返回
		if($cacheData){
			return $cacheData;
		}

		//如果没有数据,去数据库查询根据primaryKey 和 id
		$mysqlData = $this->dbLayer->select($this->primaryKey.'='.$id,'*');

		//如果数据为空,返回false
		if(empty($mysqlData)){
			return false;
		}
		//数据存入缓存
		$this->cacheLayer->save($id,$mysqlData);
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

		list($hits, $miss) = $this->cacheLayer->getList($ids);

		if($miss){
				//未缓存数据从数据库读取
			$missRows = $this->dbLayer->select($this->primaryKey.' in (' . implode(',', $miss) . ')', '*');
			if($missRows){
				foreach ($missRows as $val){
					//添加memcache缓存数据
					$this->cacheLayer->save($val[$this->primaryKey],$val);
				}
				$hits = array_merge($hits, $missRows);
			}
		}

		$resArray = array();
		if($hits){
			//按该页要显示的id排序
			$result = array();
			foreach ($hits as $val) {
				$result[$this->primaryKey] = $val;
			}
			
			//按照传入id列表初始顺序排序
			foreach ($ids as $val){
				if($result[$val]){
					$resArray[] = $result[$val];
				}
			}
			unset($result);
		}
		return $resArray;
	}	
}

?>