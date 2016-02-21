<?php
namespace Query\User\FragmentCache;

use Core;
/**
 * 工厂解耦领域服务直接对命令的调用
 */

class UserFragmentFactory {

	//$order is array(get,refresh,del)
	public static function createFragment($type,$method) {

		switch ($type) {
			case 'count' ://客户注册
				return Core::$_container->call(['Query\User\FragmentCache\UserCountFragmentQuery',$method]);
				break;
		}
	}
}