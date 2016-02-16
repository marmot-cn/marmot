<?php
namespace System\Command\Cache;
use Core;
/**
 * 批量获取cache命令
 * @author chloroplast1983
 *
 */

class GetListMemcacheCommand implements Command {
	
	private $key;
	private $idList;
	
	public function __construct($key,$idList){
		$this->key = $key;
		$this->idList = $idList;
	}

	public function execute() {
		$keys = array();
		foreach ($this->idList as $key => $val){
			$keys[$val] = $this->key . '_' . $val;
		}
		
		return Core::$_cacheDriver->fetchMultiple($this->key);
	}

	public function undo() {
		//
	}
}
?>