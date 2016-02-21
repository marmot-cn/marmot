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
		
		$command = Core::$_container->call(['Command\User\UserCommandFactory','createCommand'],['type'=>'regist','data'=>$this->user]);
		$command->execute();
	}

	public function login(){

		//command
		// $command = Core::$_container->make('Command\User\UserLoginCommand',['user'=>$this->user]);
		// return $command->execute();//登录 
	}


}
?>