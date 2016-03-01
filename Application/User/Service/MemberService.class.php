<?php
namespace User\Service;
use User\Model\User;
use Core;

/**
 * 用户会员身份功能,包括更新用户信息和升级功能
 * 
 * @author chloroplast
 * @version 1.0.0:20160227
 */
class MemberService implements MemberServiceInterface{

	/**
	 * @var User $user 用户对象
	 */
	private $user;

	public function __construct(){
		$this->user = new User();
	}

	public function updateProfile(int $id,int $avatarId, string $realName,string $provinceId,string $cityId, string $districtId,string $email,string $qq){

		//赋值用户id
		$this->user->setId($id);
		//头像id
		$this->user->setAvatar($avatarId);
		//赋值真实姓名	
		$this->user->setRealName($realName);	
		//赋值省
		$this->user->getProvince()->setId($provinceId);
		//赋值市
		$this->user->getCity()->setId($cityId);
		//赋值区
		$this->user->getDistrict()->setId($districtId);
		//赋值邮箱
		$this->user->setEmail($email);
		//赋值qq
		$this->user->setQq($qq);

		//调用更新用户信息命令
		$command = Core::$_container->call(['User\Command\UserCommandFactory','createCommand'],['type'=>'updateProfile','data'=>$this->user]);
		return $command->execute();
	}

	/**
	 * 更新用户密码
	 * @param int $id 用户id
	 * @param string $password 用户密码
	 * @return bool 返回是否更新密码成功
	 */
	public function updatePassword(int $id,string $password){
		//赋值用户id
		$this->user->setId($id);
		//赋值用户密码
		$this->user->setPassword($password);

		//调用用户更新密码命令
		$command = Core::$_container->call(['User\Command\UserCommandFactory','createCommand'],['type'=>'updatePassword','data'=>$this->user]);
		return $command->execute();		
	}
}
?>