<?php
/**
 * 测试未注册用户服务,我们需要测试:
 * 1. 测试private $user 是否正确赋值
 * 2. 注册功能
 * 3. 登录功能
 */
class UnRegisterUserServiceCommandTest extends GenericTestsDatabaseTestCase{

	public $fixtures = array('pcore_user');
	
    public function tearDown(){
    	Core::$_cacheDriver->flushAll();
    }

	public function testPrivateUserProperty(){

		$userName = 'chloroplast';
		$password = '111111';

		$unregistUserService = new Service\User\UnRegisterUserService($userName,$password);

		$property = $this->getPrivateProperty('Service\User\UnRegisterUserService', 'user');

		$this->assertEquals($property->getValue($unregistUserService)->getUserName(), $userName);
		$this->assertNotEmpty($property->getValue($unregistUserService)->getPassword());//密码不为空
		$this->assertNotEquals($property->getValue($unregistUserService)->getPassword(), $password);//密码加密过,所以不同
	}

	/**
	 * 需要测试片段缓存
	 */
	public function testRegist(){
		//初始化User
		$userName = 'chloroplast';
		$password = '111111';

		//查询用户总数
		$oldUserCount = Core::$_dbDriver->query('SELECT COUNT(*) as count FROM pcore_user');
		$oldUserCount = $oldUserCount[0]['count'];
		//获取片段缓存
		$userRepository = Core::$_container->get('Query\User\UserRepository');
		$count = $userRepository->getUserCount();
		$this->assertEquals($oldUserCount,$count);

		$unregistUserService = new Service\User\UnRegisterUserService($userName,$password);
		$unregistUserService->regist();

		$userRepository = Core::$_container->get('Query\User\UserRepository');
		$count = $userRepository->getUserCount();
		//检测注册后是否数据+1,片段缓存更新
		$this->assertEquals($oldUserCount+1,$count);	
	}
}