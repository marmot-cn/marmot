<?php
namespace Model\User;

use System\Core;


class User {

	private $uid;
	private $userName;
	private $password;

	public function __construct(){
		$this->uid = 0;
		$this->userName = '';
		$this->password = '';
	}

	public function __destruct(){
		unset($this->uid);
		unset($this->userName);
		unset($this->password);
	}

	public function setUid($uid){
		$this->uid = $uid;
	}

	public function getUid(){
		return $this->uid;
	}

	public function setUserName($userName){
		$this->userName = $userName;
	}

	public function getUserName(){
		return $this->userName;
	}

	private function encryptPassword($password){
		return md5($this->password);
	}

	public function setPassword($password){
		$this->password = $this->encryptpassword($password);
	}

	public function getPassword(){
		return $this->password;
	}
}
?>