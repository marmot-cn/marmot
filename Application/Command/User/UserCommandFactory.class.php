<?php
namespace Command\User;
use Command\User;
use Core;

/**
 * 工厂解耦领域服务直接对命令的调用
 */

class UserCommandFactory {

	private static $pcommand;

	public static function createCommand($type,$data) {
		switch ($type) {
			case 'regist' ://客户注册
				self::$pcommand = Core::$_container->make('Command\User\UserRegistCommand',['user'=>$data]);
				break;
			case 'login' :
				self::$pcommand = Core::$_container->make('Command\User\UserLoginCommand',['user'=>$data]);
				break;
		}
		return self::$pcommand;
	}
}