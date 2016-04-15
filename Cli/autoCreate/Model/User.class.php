<?php
namespace User\Model;

/**
 * User 用户领域对象
 * @author chloroplast
 * @version 1.0.0:2016.04.15
 */

class User{

	/**
	 * @var int $id 用户id
	 */
	private $id
	/**
	 * @var string $name 用户名字
	 */
	private $name
	/**
	 * @var string $cellPhone 用户手机号
	 */
	private $cellPhone
	/**
	 * @var string $qq 用户qq
	 */
	private $qq
	/**
	 * @var string $email 用户邮箱
	 */
	private $email
	/**
	 * @var int $createTime 用户注册时间
	 */
	private $createTime
	/**
	 * @var int $status 用户状态
	 */
	private $status
	/**
	 * @var Area\Model\Area $district 用户住址区
	 */
	private $district

	/**
	 * User 用户领域对象 构造函数
	 */
	public function __construct(){
		global $_FWGLOBAL;
		$this->id = 0;
		$this->name = '';
		$this->cellPhone = '';
		$this->qq = '';
		$this->email = '';
		$this->createTime = $_FWGLOBAL['timestamp'];
		$this->status = USER_STATUS_NORMAL;
		$this->district = '';
	}

	/**
	 * User 用户领域对象 析构函数
	 */
	public function __destruct(){
		unset($this->id);
		unset($this->name);
		unset($this->cellPhone);
		unset($this->qq);
		unset($this->email);
		unset($this->createTime);
		unset($this->status);
		unset($this->district);
	}

	/**
	 * 设置用户id
	 * @param int $id 用户id
	 */
	public function setId(int $id){
	}

	/**
	 * 获取用户id
	 * @return int $id 用户id
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * 设置用户名字
	 * @param string $name 用户名字
	 */
	public function setName(string $name){
	}

	/**
	 * 获取用户名字
	 * @return string $name 用户名字
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * 设置用户手机号
	 * @param string $cellPhone 用户手机号
	 */
	public function setCellPhone(string $cellPhone){
		$this->cellPhone = is_numeric($cellPhone) ? $cellPhone : ''
	}

	/**
	 * 获取用户手机号
	 * @return string $cellPhone 用户手机号
	 */
	public function getCellPhone(){
		return $this->cellPhone;
	}

	/**
	 * 设置用户qq
	 * @param string $qq 用户qq
	 */
	public function setQq(string $qq){
		$this->qq = is_numeric($qq) ? $qq : ''
	}

	/**
	 * 获取用户qq
	 * @return string $qq 用户qq
	 */
	public function getQq(){
		return $this->qq;
	}

	/**
	 * 设置用户邮箱
	 * @param string $email 用户邮箱
	 */
	public function setEmail(string $email){
		$this->email= filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : ''
	}

	/**
	 * 获取用户邮箱
	 * @return string $email 用户邮箱
	 */
	public function getEmail(){
		return $this->email;
	}

	/**
	 * 设置用户注册时间
	 * @param int $createTime 用户注册时间
	 */
	public function setCreateTime(int $createTime){
		$this->createTime = $createTime;
	}

	/**
	 * 获取用户注册时间
	 * @return int $createTime 用户注册时间
	 */
	public function getCreateTime(){
		return $this->createTime;
	}

	/**
	 * 设置用户状态
	 * @param int $status 用户状态
	 */
	public function setStatus(int $status){
		$this->status= in_array($status,array(USER_STATUS_NORMAL,USER_STATUS_BANNED)) ? $status : USER_STATUS_NORMAL;
	}

	/**
	 * 获取用户状态
	 * @return int $status 用户状态
	 */
	public function getStatus(){
		return $this->status;
	}

	/**
	 * 设置用户住址区
	 * @param Area\Model\Area $district 用户住址区
	 */
	public function setDistrict(Area\Model\Area $district){
		$this->district = $district;
	}

	/**
	 * 获取用户住址区
	 * @return Area\Model\Area $district 用户住址区
	 */
	public function getDistrict(){
		return $this->district;
	}

}