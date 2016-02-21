<?php
namespace Command\user;
use System\Interfaces\Pcommand;
use Model\User;
use Core;

class UserLoginCommand implements Pcommand{
	
	private $user;

	/**
	 * @Inject("Persistence\User\UserDb")
	 */
	private $dbLayer;//数据层
	
	public function __construct($user){
		$this->user = $user;
	}

	public function execute(){

		$mysqlDataArray = array('userName'=>$this->user->getUserName(),
								'passWord'=>$this->user->getPassword());
		
		$userInfo = $this->dbLayer->select('(userName=\''.$this->user->getUserName().'\) AND password=\''.$this->user->getPassword().'\'');

		if(empty($userInfo)){
			return false;
		}
		//如果用户还有其他参数赋值给用户
		//$this->user->setXXX

		//处理cookie
		//如果使用内存表作为存储用户数据(不使用session),插入内存表
		return true;
	}

	public function report(){

	}
}