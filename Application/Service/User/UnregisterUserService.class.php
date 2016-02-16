<?php

namespace Service\User;

class UnregisterUserService implements UnregisterUserServiceInterface{

	private $user;

	public function __construct(\Model\User\User $user){
		echo 'load UnregisterUserService';
		$this->user = $user;
	}

	public function regist(){

	}

	public function login(){

	}
}
?>