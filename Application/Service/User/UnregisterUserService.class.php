<?php

namespace Service\User;
use Model\User;
use Core;

class UnregisterUserService implements UnregisterUserServiceInterface{

	private $user;

	public function __construct($userName,$password){
		$this->user = new User\User();
		$this->user->setUserName($userName);
		$this->user->setPassWord($password);
	}

	public function regist(){
		
		//command
		$command = Core::$_container->make('Command\User\UserRegistCommand',['user'=>$this->user]);
		if($command->execute()){//注册
			//这里暂时用主动更新片段缓存,稍后可以写成存储在redis的观察者模式更新缓存
			Core::$_container->call(['Query\User\UserCountFragmentQuery','refresh']);
		}
	}

	public function login(){

		//command
		$command = Core::$_container->make('Command\User\UserLoginCommand',['user'=>$this->user]);
		return $command->execute();//登录
	}


}
?>