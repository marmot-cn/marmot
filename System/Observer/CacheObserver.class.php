<?php
namespace System\Observer;
use System\Interface;
/**
 * 全站观察者文件,需要统一函数update
 */

/**
 * 缓存memcache观察者
 * @author chloroplast1983
 *
 */
class CacheObserver implements observer{
	
	private $cacheCommand;
	
	public function __construct(Command $cacheCommand){
		$this->cacheCommand = $cacheCommand;
	}
	public function update(){
		if($this->cacheCommand instanceof Command){
			$this->cacheCommand->undo();
		}
	}
}

?>