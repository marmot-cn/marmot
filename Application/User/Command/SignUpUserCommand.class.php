<?php
namespace User\Command;
use System\Interfaces\Pcommand;
use User\Model\User;
/**
 * 用户注册命令
 * @author chloroplast
 * @version 1.0.20160222
 */

class SignUpUserCommand implements Pcommand{

	private $user;

	/**
	 * @Inject("User\Persistence\UserDb")
	 */
	private $dbLayer;

	/**
	 * @Inject("User\Persistence\UserCache")
	 */
	private $cacheLayer;

	public function __construct(User $user){
		$this->user = $user;
	}

	public function execute(){
		//拼接数据库数组
		$mysqlDataArray = array('cellPhone'=>$this->user->getCellPhone(),
								'salt'=>$this->user->getSalt(),
								'password'=>$this->user->getPassword(),
								'createTime'=>$this->user->getCreateTime());

		////写入数据库,如果成功,写入缓存
		$id = $this->dbLayer->insert($mysqlDataArray,true);
		if(!$id){
			return false;
		}

		//返回用户主键id,写会$user对象,为领域服务间互相调用服务
		$this->user->setId($id);
		
		return true;
	}

	public function report(){

	}
}