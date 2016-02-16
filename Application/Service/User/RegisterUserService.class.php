<?php

namespace Service\User;

/**
 * 领域服务
 */
class RegisterUserService implements RegisterUserServiceInterface{

	private $user;

	public function __construct(\Model\User\User $user){
		$this->user = $user;
	}
}
?>