<?php
namespace User\Service;
use User\Model\User;
use User\Repository;
use Core;
use Common;

/**
 * 用户游客身份功能,包括注册,登录和验证功能.
 * 验证功能对应的是
 * 1. 注册:手机短信注册
 * 2. 登录:验证码
 * 
 * @author chloroplast
 * @version 1.0.0:20160227
 */
class GuestService implements GuestServiceInterface {

	/**
	 * @var User $user 用户对象
	 */
	private $user;


	public function __construct(){
		$this->user = new User();
	}

	/**
	 * 注册功能
	 * @param string $cellPhone 手机号
	 * @param string $password 密码
	 * @return integer | bool 执行成功返回新的用户id,执行失败返回false
	 */
	public function signUp(string $cellPhone,string $password,string $code){

		//调用注册验证功能
		$registerService = new Common\Service\RegisterSmsService();
		if(!$this->verify($code,$registerService)){
			return false;
		}
		//设置用户手机
		$this->user->setCellPhone($cellPhone);
		//设置用户密码
		$this->user->setPassword($password);

		//注册命令
		$command = Core::$_container->call(['User\Command\UserCommandFactory','createCommand'],['type'=>'signUp','data'=>$this->user]);
		if($command->execute()){
			return $this->user->getId();
		}
		return false;
	}

	/**
	 * 登录功能
	 * @param string $cellPhone 手机号
	 * @param string $password 密码
	 */
	public function signIn(string $cellPhone,string $password){

		//根据手机号返回盐和加密过的密码
		$repository = Core::$_container->get('User\Repository\UserRepository');
		$userInfo = $repository->select('cellPhone=\''.$cellPhone.'\'','salt,password');
		if(!empty($userInfo)){
			$this->user->setPassword($password,$userInfo[0]['salt']);
			//验证和盐加密过的密码是否和数据库密码一致
			if($userInfo[0]['password']==$this->user->getPassword()){
				return true;
			}
		}	
		return false;
	}

	/**
	 * 验证功能
	 * @param string $code 验证码
	 */
	private function verify(string $code,Common\Service\VerifyCodeInterface $verifyCodeInterface){
		
		//验证
		return $verifyCodeInterface->verify($code);
	}

	/**
	 * 重置密码
	 */
	public function restPassword(string $cellPhone,string $password,string $code){

		//调用找回密码验证功能
		$restPasswordService = new Common\Service\RestPasswordSmsService();
		if(!$this->verify($code,$restPasswordService)){
			return false;
		}
		//确认手机号已经注册过
		$repository = Core::$_container->get('User\Repository\UserRepository');
		$userInfo = $repository->select('cellPhone=\''.$cellPhone.'\'','id');
		if(empty($userInfo)){
			return false;
		}

		//设置用户手机
		$this->user->setId($userInfo[0]['id']);
		//设置用户密码
		$this->user->setPassword($password);

		//修改密码命令
		$command = Core::$_container->call(['User\Command\UserCommandFactory','createCommand'],['type'=>'updatePassword','data'=>$this->user]);
		return $command->execute();
	}
}