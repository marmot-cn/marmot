<?php
namespace Controller;
use Core;
	
class UserController {

	/**
	 * 测试调取用户信息页面
	 */
	public function index(){

		$userCountFragmentQuery = Core::$_container->get('Query\User\UserCountFragmentQuery');
		$userCountFragmentData = $userCountFragmentQuery -> get();
		var_dump($userCountFragmentData);
		exit();
	}

	public function login($userName,$password){

		$unregistUser = new Service\User\UnRegisterUserService($userName,$password);
		$unregistUser -> regisit();
	}

	public function regisit($userName,$password){
		//验证是否符合规则,因为验证规则和业务逻辑并无关联,所以我在应用层里面验证传参
		
		$unregistUser = new Service\User\UnRegisterUserService($userName,$password);
		$unregistUser -> regisit();
	}

	public function profile($uid){
		//这里演示,用的是传参的uid
		//验证uid

		$userQuery = Core::$_container->get('Query\User\UserQuery');

		var_dump($userQuery->getOne($uid));
		exit();
	}
}