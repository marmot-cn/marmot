<?php
//powered by kevin
namespace System\Core;
use System\Command\Cache;

class Cache {
	
	protected static $key = 'phpcore';//默认phpcore 
	
	/** 
	 * 为缓存写入一个值
	 * @param string $id 缓存id
	 * @param mixed $data 缓存内容
	 * @param integer $time 缓存存在时间,默认为0
	 * @author chloroplast1983
	 * @version 1.0.20131017
	 */
	public static function add($id, $data, $time=0) {
		$command = new AddCacheCommand(static::$key . '_' . $id, $data,$time);
		return $command -> execute();
	}
	
	/**
	 * 根据id删除缓存一个值
	 * @param string $id
	 */
	public static function del($id) {
		$command = new DelCacheCommand(static::$key . '_' . $id);
		return $command -> execute();
	}
	
	/**
	 * 根据id读取缓存一个值
	 * @param string $id
	 */
	public static function get($id) {
		$command = new GetCacheCommand(static::$key . '_' . $id);
		return $command -> execute();
	}
	
	/** 
	 * 根据id,覆盖缓存一个值
	 * @param string $id 缓存id
	 * @param mixed $data 缓存内容
	 * @param integer $time 缓存存在时间,默认为0
	 * @author chloroplast1983
	 * @version 1.0.20131017
	 */
	public static function replace($id, $data, $time=0) {
		$command = new ReplaceCacheCommand(static::$key . '_' . $id, $data, $time);
		return $command -> execute();
	}
	
	public static function getList($idList){
		$command = new GetListMemcacheCommand(static::$key,$idList);
		return $command -> execute();
	}
}
?>