<?php
namespace User\Model;

/**
 * User 用户领域对象
 * @author chloroplast
 * @version 1.0.0:2016.04.16
 */

class User{

	/**
	 * @var int $id 用户id
	 */
	private $id;
	/**
	 * @var string $password 用户密码
	 */
	private $password;
	/**
	 * @var string $cellPhone 用户手机号
	 */
	private $cellPhone;
	/**
	 * @var int $signUpTime 用户注册时间
	 */
	private $signUpTime;
	/**
	 * @var string $nickName 用户昵称
	 */
	private $nickName;
	/**
	 * @var string $userName 用户名
	 */
	private $userName;

	public $bank;

	/**
	 * User 用户领域对象 构造函数
	 */
	public function __construct(){
		global $_FWGLOBAL;
		$this->id = 0;
		$this->password = '';
		$this->cellPhone = '';
		$this->signUpTime = $_FWGLOBAL['timestamp'];
		$this->nickName = '';
		$this->userName = '';
	}

	/**
	 * User 用户领域对象 析构函数
	 */
	public function __destruct(){
		unset($this->id);
		unset($this->password);
		unset($this->cellPhone);
		unset($this->signUpTime);
		unset($this->nickName);
		unset($this->userName);
	}

	/**
	 * 设置用户id
	 * @param int $id 用户id
	 */
	public function setId(int $id){
		$this->id = $id;
	}

	/**
	 * 获取用户id
	 * @return int $id 用户id
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * 设置用户密码
	 * @param string $password 用户密码
	 */
	public function setPassword(string $password){
		$this->password = $password;
	}

	/**
	 * 获取用户密码
	 * @return string $password 用户密码
	 */
	public function getPassword(){
		return $this->password;
	}

	/**
	 * 设置用户手机号
	 * @param string $cellPhone 用户手机号
	 */
	public function setCellPhone(string $cellPhone){
		$this->cellPhone = is_numeric($cellPhone) ? $cellPhone : '';
	}

	/**
	 * 获取用户手机号
	 * @return string $cellPhone 用户手机号
	 */
	public function getCellPhone(){
		return $this->cellPhone;
	}

	/**
	 * 设置用户注册时间
	 * @param int $signUpTime 用户注册时间
	 */
	public function setSignUpTime(int $signUpTime){
		$this->signUpTime = $signUpTime;
	}

	/**
	 * 获取用户注册时间
	 * @return int $signUpTime 用户注册时间
	 */
	public function getSignUpTime(){
		return $this->signUpTime;
	}

	/**
	 * 设置用户昵称
	 * @param string $nickName 用户昵称
	 */
	public function setNickName(string $nickName){
		$this->nickName = $nickName;
	}

	/**
	 * 获取用户昵称
	 * @return string $nickName 用户昵称
	 */
	public function getNickName(){
		return $this->nickName;
	}

	/**
	 * 设置用户名
	 * @param string $userName 用户名
	 */
	public function setUserName(string $userName){
		$this->userName = $userName;
	}

	/**
	 * 获取用户名
	 * @return string $userName 用户名
	 */
	public function getUserName(){
		return $this->userName;
	}

}