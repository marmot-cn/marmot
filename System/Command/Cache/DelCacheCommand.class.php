<?php
namespace System\Command\Cache;
use Core;
/**
 * 删除cache缓存命令
 * @author chloroplast1983
 *
 */

class DelCacheCommand implements Command {
	
	private $key;
	
	public function __construct($key){
		$this->key = $key;
	}

	public function execute() {
		return Core::$_cacheDriver->delete($this->key);
	}

	public function undo() {
		//
	}
}
?>