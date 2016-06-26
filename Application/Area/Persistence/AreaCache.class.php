<?php
namespace Area\Persistence;

use System\Classes\Cache;
/**
 * area表缓存层文件
 * @author chloroplast
 * @version 1.0.20160223
 */
class AreaCache extends Cache{
	
	public function __construct(){
		parent::__construct('area');
	}
}
?>