<?php
/**
 * User/Service/MemberService.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */
class MemberServiceTest extends GenericTestsDatabaseTestCase{

	public $fixtures = array('pcore_user');

	private $service;

	private $userCacheLayer;

	public function setUp(){    

		//初始化service
		$this->service = new User\Service\MemberService();

		//初始化用户缓存,为了测试一些服务正常删除缓存,使用用户id为3的用户测试缓存
		$this->userCacheLayer = new User\Persistence\UserCache();
		//1号用户缓存,用于测试VIP用户升级
		$this->userCacheLayer->save(1,'test');
		//3号用户缓存,用于测试非VIP用户升级
		$this->userCacheLayer->save(3,'test');

		parent::setUp();
	}

	public function tearDown(){

		//清空缓存数据
    	Core::$_cacheDriver->flushAll();
		parent::tearDown();
	}

	//profile -- 开始
	/**
     * 更新用户信息,期望:
     * 1. 命令返回成功
     * 2. 缓存删除成功
     * 3. 数据库更新成功
     */	
	public function testMemberServiceUpdateProfileWithNewProfile(){

		//需要测试的用户id
		$testUserId = 1;
		//更新用户信息
		$updateUserProfileInfo = array('avatarId'=>2,
									   'realName'=>'李四',
									   'provinceId'=>11,
									   'cityId'=>149,
									   'districtId'=>1255,
									   'email'=>'15@qq.com',
									   'qq'=>'1348107675');
		//调用服务
		$result = $this->service->updateProfile($testUserId,$updateUserProfileInfo['avatarId'],$updateUserProfileInfo['realName'],$updateUserProfileInfo['provinceId'],$updateUserProfileInfo['cityId'],$updateUserProfileInfo['districtId'],$updateUserProfileInfo['email'],$updateUserProfileInfo['qq']);

		//期望命令返回成功
		$this->assertTrue($result);

		//确认缓存删除成功
		$this->assertEmpty($this->userCacheLayer->get($testUserId));

		//查询新用户的数据
		$newUserProfileInfo = Core::$_dbDriver->query('SELECT avatarId,realName,provinceId,cityId,districtId,email,qq FROM pcore_user WHERE id='.$testUserId);
		$newUserProfileInfo = $newUserProfileInfo[0];
		//确认更新后的用户profile数据一致
		$this->assertEquals($updateUserProfileInfo,$newUserProfileInfo);
	}

	/**
     * 重复更新用户信息(和用户旧数据一致),期望:
     * 1. 命令返回失败
     * 2. 缓存删除失败
     * 3. 数据库库更新失败
     */
	public function testMemberServiceUpdateProfileWithOldProfile(){
		
		//需要测试的用户id
		$testUserId = 1;

		$oldNewProfileInfo = Core::$_dbDriver->query('SELECT avatarId,realName,provinceId,cityId,districtId,email,qq FROM pcore_user WHERE id='.$testUserId);
	    $oldNewProfileInfo = $oldNewProfileInfo[0];

	    //调用服务
		$result = $this->service->updateProfile($testUserId,$oldNewProfileInfo['avatarId'],$oldNewProfileInfo['realName'],$oldNewProfileInfo['provinceId'],$oldNewProfileInfo['cityId'],$oldNewProfileInfo['districtId'],$oldNewProfileInfo['email'],$oldNewProfileInfo['qq']);

		//期望命令返回失败
		$this->assertFalse($result);

		//确认缓存没有删除
		$this->assertEquals('test',$this->userCacheLayer->get($testUserId));

		//查询新用户的数据
		$newUserProfileInfo = Core::$_dbDriver->query('SELECT avatarId,realName,provinceId,cityId,districtId,email,qq FROM pcore_user WHERE id='.$testUserId);
		$newUserProfileInfo = $newUserProfileInfo[0];
		//确认用户profile没有更新
		$this->assertEquals($oldNewProfileInfo,$newUserProfileInfo);
	}
	//profile -- 结束

	//updatePassword -- 开始
	/**
	 * 测试用户修改密码,生成新的salt和加密的密码和原来的数据不一致
	 */
	public function testMemberServiceUpdatePassword(){

		$testUserId = 3;

		//查询旧用户的数据(密码 和 盐)
		$lastUserInfo = Core::$_dbDriver->query('SELECT password,salt FROM pcore_user WHERE id='.$testUserId);

		//调用服务
		$result = $this->service->updatePassword($testUserId,'111111');

		//期望命令返回成功
		$this->assertTrue($result);

		//确认缓存删除成功
		$this->assertEmpty($this->userCacheLayer->get($testUserId));

		//查询新用户的数据(密码 和 盐)
		$newUserInfo = Core::$_dbDriver->query('SELECT password,salt FROM pcore_user WHERE id='.$testUserId);

		//确认密码和盐不为空
		$this->assertNotEmpty($newUserInfo[0]['password']);
		$this->assertNotEmpty($newUserInfo[0]['salt']);

		//确认和旧的用户 密码 盐 都不一致
		$this->assertNotEquals($lastUserInfo[0]['password'],$newUserInfo[0]['password']);
		$this->assertNotEquals($lastUserInfo[0]['salt'],$newUserInfo[0]['salt']);
	}
	//updatePassword -- 结束
}
?>