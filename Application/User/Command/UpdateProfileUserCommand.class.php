<?php
namespace User\Command;
use System\Interfaces\Pcommand;
use User\Model\User;
/**
 * 用户修改信息命令
 * @author chloroplast
 * @version 1.0.20160222
 */
class UpdateProfileUserCommand implements Pcommand{

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
		$mysqlDataArray = array('avatarId'=>$this->user->getAvatar(),
								'realName'=>$this->user->getRealName(),
								'provinceId'=>$this->user->getProvince()->getId(),
								'cityId'=>$this->user->getCity()->getId(),
								'districtId'=>$this->user->getDistrict()->getId(),
								'email'=>$this->user->getEmail(),
								'qq'=>$this->user->getQq());
		//拼接更新条件数组
		$conditionArray = array('id'=>$this->user->getId());

		$row = $this->dbLayer->update($mysqlDataArray,$conditionArray);
		if(!$row){
			return false;
		}
		
		//如果更新成功,删除缓存,这里暂时不重写缓存等后续有时间在更新
		$this->cacheLayer->del($this->user->getId());
		return true;
	}

	public function report(){

	}
}