<?php
namespace System\Command\Cache;
use Core;
/**
 * 更新cache缓存命令
 * @author chloroplast1983
 *
 */

class ReplaceCacheCommand implements Command {
	
	private $key;
	private $data;
	private $time;
	
	public function __construct($key,$data,$time=0){
		global $memcache;
		$this->key = $key;
		$this->data = $data;
		$this->time = $time;
		
		if(transaction::inTransaction()){
			transaction::$transactionSubject -> attach(new memcacheObserver($this));
		}
	}

	public function execute() {
		return Core::$_cacheDriver->save($this->key, $this->data,$this->time);
	}

	public function undo() {
		return Core::$_cacheDriver->delete($this->key);
	}
}
?>