<?php
namespace Command\user;
use Model\User;

class UserRegistCommand {
	
	private $user;

	/**
	 * @Inject("Persistence\User\UserCache")
	 */
	private $cacheLayer;//缓存层	

	/**
	 * @Inject("Persistence\User\UserDb")
	 */
	private $dbLayer;//数据层

	public function __construct(User\User $user){
		$this->user = $user;
	}

	public function execute(){
		//写入数据库
		$mysqlDataArray = array('userName'=>$this->user->getUserName(),
								'password'=>$this->user->getPassword());

		if(empty($mysqlDataArray['userName'])||empty($mysqlDataArray['password'])){
			return false;
		}
		//如果成功,写入缓存
		$uid = $this->dbLayer->insert($mysqlDataArray,true);
		if(!$uid){
			return false;
		}
		//保存缓存
		$this->cacheLayer->save($uid,$mysqlDataArray);
		//返回用户主键id,写会$user对象,为领域服务间互相调用服务
		$this->user->setUid($uid);
		
		return true;
	}
}