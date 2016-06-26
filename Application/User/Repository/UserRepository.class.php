<?php
namespace User\Repository;
use User\Repository\Query;
use Common;

/**
 * 用户表仓库
 * 
 * @author chloroplast
 * @version 1.0:20160227 
 */
class UserRepository{

	/**
	 * @var Query\UserRowCacheQuery $userRowCacheQuery 行缓存
	 */
	private $userRowCacheQuery;

	public function __construct(Query\UserRowCacheQuery $userRowCacheQuery){
		$this->userRowCacheQuery = $userRowCacheQuery;
	}

	/**
	 * 获取用户信息
	 * @param integer $id 用户id
	 */
	public function getOne($id){

		//初始化返回结果
		$result = array();
		$avatarPath = '';

		//获取用户数据
		$userInfo = $this->userRowCacheQuery->getOne($id);
		
		//调用头像的领域服务 -- 开始
		if(!empty($userInfo['avatarId'])){
			//$avatarPath = $userInfo['avatarId'=>头像地址id]转换为头像地址
			$avatarFileService = new Common\Service\AvatarFileService();
			$avatarPath = $avatarFileService->getOne($userInfo['avatarId']);
		}
		//调用头像的领域服务 -- 结束
		//查询地区信息		
		//构建返回结果 
		$result['avatar'] = $avatarPath;
		$result['cellPhone'] = $userInfo['cellPhone'];
		$result['realName'] = $userInfo['realName'];
		$result['provinceId'] = $userInfo['provinceId'];
		$result['cityId'] = $userInfo['cityId'];
		$result['districtId'] = $userInfo['districtId'];
		$result['email'] = $userInfo['email'];
		$result['qq'] = $userInfo['qq'];
		return $result;
	}

	/**
	 * 查询DB层,select
	 * 
	 * @param string $sql 查询语句
	 * @param string $select 查询内容
	 * @param string $useIndex 额外使用索引
	 * 
	 * @return [] 返回查询结果
	 */
	public function select(string $sql,string $select='*',string $useIndex=''){
		return $this->userRowCacheQuery->select($sql,$select,$useIndex);
	}
}