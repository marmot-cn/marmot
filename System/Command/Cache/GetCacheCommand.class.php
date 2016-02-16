<?php
namespace System\Command\Cache;
use Core;
/**
 * 获取单条Cache命令
 * @author chloroplast1983
 *
 */

class GetCacheCommand implements Command {
	
	private $key;
	
	public function __construct($key){
		$this->key = $key;
	}

	public function execute() {
		return Core::$_cacheDriver->fetch($this->key);
	}

	public function undo() {
		//
	}
}
?>