<?php
/**
 * User/Service/GuestService.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */
class GuestServiceTest extends GenericTestsDatabaseTestCase{

	public $fixtures = array('pcore_user');

	private $service;

	private $userCacheLayer;

	public function setUp(){    

		//初始化service
		$this->service = new User\Service\GuestService();

		//初始化用户缓存,为了测试一些服务正常删除缓存,使用用户id为3的用户测试缓存
		$this->userCacheLayer = new User\Persistence\UserCache();
		$this->userCacheLayer->save(3,'test');

		//初始化session
		$_SESSION['registerSession'] = 'testRegisterCode';
		$_SESSION['restPasswordSession'] = 'testRestPasswordCode';
		parent::setUp();
	}

	public function tearDown(){
		unset($_SESSION['registerSession']);
		unset($_SESSION['restPasswordSession']);
		//清空缓存数据
    	Core::$_cacheDriver->flushAll();
		parent::tearDown();
	}

	/**
	 * 测试游客身份注册功能 GuestService signUp() 错误的验证码,期望返回失败,用户数据库数据不变
	 */
	public function testGuestServiceSignUpWithWrongVerifyCode(){

		//旧的用户总数
		$oldCount = Core::$_dbDriver->query('SELECT COUNT(*) as count FROM pcore_user');
		$oldCount = $oldCount[0]['count'];

		//调用服务
		$result = $this->service->signUp('15202939435','111111','testWrongRegisterCode');

		//期望结果返回false
		$this->assertFalse($result);

		//查询数据库,确认数据没有插入成功
		//新的用户总数
		$newCount = Core::$_dbDriver->query('SELECT COUNT(*) as count FROM pcore_user');
		$newCount = $newCount[0]['count'];

		//期望旧的用户总数和新的用户总数一致没有变化
		$this->assertEquals($oldCount,$newCount);
	}

	/**
	 * 测试游客身份注册功能 GuestService signUp() 正确的验证码,期望返回正确,用户数据库数据插入新的数据
	 */
	public function testGuestServiceSignUpWithCorrectVerifyCode(){

		//调用服务
		$id = $this->service->signUp('15202939435','111111','testRegisterCode');

		//期望uid已经赋值且大于0
		$this->assertGreaterThan(0,$id);

		//查询数据库,确认数据插入成功
		$result = Core::$_dbDriver->query('SELECT * FROM pcore_user WHERE id='.$id);

		$this->assertEquals($id,$result[0]['id']);
		$this->assertEquals('15202939435',$result[0]['cellPhone']);
		$this->assertNotEmpty($result[0]['salt']);
		$this->assertNotEmpty($result[0]['createTime']);
	}

	/**
	 * 测试游客身份注册功能 GuestService signUp() 正确的验证码,重复的手机号,期望返回失败,用户数据库数据不变
	 */
	public function testGuestServiceSignUpWithCorrectVerifyCodeDuplicateCellPhoneNumber(){

		//旧的用户总数
		$oldCount = Core::$_dbDriver->query('SELECT COUNT(*) as count FROM pcore_user');
		$oldCount = $oldCount[0]['count'];

		//查询出一个已经使用过的手机号
		$existedCellPhoneNumber = Core::$_dbDriver->query('SELECT cellPhone FROM pcore_user LIMIT 1');
		$existedCellPhoneNumber = $existedCellPhoneNumber[0]['cellPhone'];
		//重新赋值一个已经存在的手机号

		//调用服务
		$result = $this->service->signUp($existedCellPhoneNumber,'111111','testRegisterCode');

		//期望结果返回false
		$this->assertFalse($result);

		//查询数据库,确认数据没有插入成功
		//新的用户总数
		$newCount = Core::$_dbDriver->query('SELECT COUNT(*) as count FROM pcore_user');
		$newCount = $newCount[0]['count'];

		//期望旧的用户总数和新的用户总数一致没有变化
		$this->assertEquals($oldCount,$newCount);
	}

	/**
	 * 测试游客身份登录功能 GuestService signIn() 正确的用户名和密码,期望返回成功
	 */
	public function testGuestServiceSignInWithCorrectPassword(){
		//pcore_user.xml 3号用户的用户名为 13571779176 密码为 123456
		//调用服务
		$result = $this->service->signIn('13571779176','123456');
		//期望返回成功
		$this->assertTrue($result);
	}

	/**
	 * 测试游客身份登录功能 GuestService signIn() 错误的用户名和密码,期望返回失败
	 */
	public function testGuestServiceSignInWithWrongPassword(){
		//pcore_user.xml 3号用户的用户名为 13571779176 密码为 123456
		//所以设定一个错误的密码1234567
		//调用服务
		$result = $this->service->signIn('13571779176','1234567');
		//期望返回成功
		$this->assertFalse($result);
	}

	/**
	 * 测试游客身份重置密码 错误的验证码
	 * 期望返回false
	 */
	public function testGuestServiceRestPasswordWithWrongVerifyCode(){

		//3号用户的用户名为 13571779176 密码为 123456
		//赋值新的密码给该用户
		//调用服务
		$result = $this->service->restPassword('13571779176','111111','testWrongRestPasswordCode');

		//期望服务返回成功
		$this->assertFalse($result);
	}

	/**
	 * 测试游客身份重置密码 正确的验证码,但是不存在的手机号
	 * 期望返回false
	 */
	public function testGuestServiceRestPasswordWithCorrectVerifyCodeAndNotExistCellPhone(){

		//3号用户的用户名为 13571779176 密码为 123456
		//赋值新的密码给该用户
		//调用服务
		$result = $this->service->restPassword('13571779171','111111','testRestPasswordCode');

		//期望服务返回成功
		$this->assertFalse($result);
	}

	/**
	 * 测试游客身份重置密码 正确的验证码和存在的手机号 GuestService restPassword() 
	 */
	public function testGuestServiceRestPasswordWithCorrectVerifyCodeAndExistCellPhone(){

		//查询旧用户的数据(密码 和 盐)
		$lastUserInfo = Core::$_dbDriver->query('SELECT password,salt FROM pcore_user WHERE id=3');

		//3号用户的用户名为 13571779176 密码为 123456
		//赋值新的密码给该用户
		//调用服务
		$result = $this->service->restPassword('13571779176','111111','testRestPasswordCode');

		//期望服务返回成功
		$this->assertTrue($result);

		//确认缓存删除成功
		$this->assertEmpty($this->userCacheLayer->get(3));

		//查询新用户的数据(密码 和 盐)
		$newUserInfo = Core::$_dbDriver->query('SELECT password,salt FROM pcore_user WHERE id=3');

		//确认密码和盐不为空
		$this->assertNotEmpty($newUserInfo[0]['password']);
		$this->assertNotEmpty($newUserInfo[0]['salt']);

		//确认和旧的用户 密码 盐 都不一致
		$this->assertNotEquals($lastUserInfo[0]['password'],$newUserInfo[0]['password']);
		$this->assertNotEquals($lastUserInfo[0]['salt'],$newUserInfo[0]['salt']);
	}
}