<?php
namespace User\Command;
use System\Interfaces\Pcommand;
use User\Model\User;

/**
 * 用户修改密码命令命令
 * @author chloroplast
 * @version 1.0.20160222
 */
class UpdatePasswordUserCommand implements Pcommand{

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
		$mysqlDataArray = array('password'=>$this->user->getPassword(),
								'salt'=>$this->user->getSalt());
		//拼接更新条件数组
		$conditionArray = array('id'=>$this->user->getId());

		$row = $this->dbLayer->update($mysqlDataArray,$conditionArray);

		//如果更新成功,删除缓存,这里暂时不重写缓存等后续有时间在更新
		$this->cacheLayer->del($this->user->getId());
		return true;
	}

	public function report(){

	}
}