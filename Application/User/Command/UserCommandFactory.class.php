<?php
namespace User\Command;
use Core;

/**
 * 工厂解耦领域服务直接对命令的调用
 * @author chloroplast
 * @version 1.0: 20160225
 */

class UserCommandFactory {

	/**
	 * @var System\Interfaces\Pcommand $command 命令
	 */
	private static $pcommand;

	/**
	 * 工厂构造命令,根据不同的type构建不同的命令返回:
	 * 
	 * 1. signUp : 注册命令
	 * 2. signIn : 登录命令
	 * 3. updatePassword: 更新密码命令
	 * 4. updateLastScore: 更新最后分数命令
	 * 5. updateProfile: 更新用户信息命令
	 * 6. upgrade: 用户升级命令
	 * 
	 * @param string $type 命令类型
	 * @param User\Model\User $data 用户对象
	 */
	public static function createCommand($type,$data) {
		switch ($type) {
			case 'signUp' :
				self::$pcommand = Core::$_container->make('User\Command\SignUpUserCommand',['user'=>$data]);
				break;
			case 'updatePassword' :
				self::$pcommand = Core::$_container->make('User\Command\UpdatePasswordUserCommand',['user'=>$data]);
				break;
			case 'updateProfile' :
				self::$pcommand = Core::$_container->make('User\Command\UpdateProfileUserCommand',['user'=>$data]);
				break;
		}
		return self::$pcommand;
	}
}