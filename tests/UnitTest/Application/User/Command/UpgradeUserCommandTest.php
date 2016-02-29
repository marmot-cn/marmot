<?php
/**
 * User/Command/UpgradeUserCommand.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */

class UpgradeUserCommandTest extends GenericTestsDatabaseTestCase{

	public $fixtures = array('pcore_user');

	private $user;

	private $userCacheLayer;

	public function setUp(){    
		//初始化user
		$this->user = new User\Model\User();
		$this->user->setId(1);//3号用户不是vip
		$this->user->setIsVip(true);

		//初始化用户缓存
		$this->userCacheLayer = new User\Persistence\UserCache();
		$this->userCacheLayer->save($this->user->getId(),'test');

		parent::setUp();
	}


 	public function tearDown(){
    	unset($this->user);
    	unset($this->userCacheLayer);
    	//清空缓存数据
    	Core::$_cacheDriver->flushAll();

    	parent::tearDown();
    }

    /**
     * 测试用户原来不是vip,升级用户命令,期望返回成功
     * 1. 期望数据库更新成功
     * 2. 期望原来缓存有数据,缓存被删除,缓存为空
     */
    public function testUpgradeWithNonVipUser(){

    	$this->user->setId(3);//3号用户不是vip

    	//查询用户旧的isVip
		$oldIsVip = Core::$_dbDriver->query('SELECT isVip FROM pcore_user WHERE id='.$this->user->getId());
		$oldIsVip = $oldIsVip[0]['isVip'];

		//期望旧的isVip字段为false
		$this->assertEquals(0,$oldIsVip);

		//初始化命令
		$command = Core::$_container->make('User\Command\UpgradeUserCommand',['user'=>$this->user]);
		//执行命令
		$result = $command->execute();

		//期望命令返回成功
		$this->assertTrue($result);

		//确认缓存删除成功
		$this->assertEmpty($this->userCacheLayer->get($this->user->getId()));

		//确认数据库更新成功
		$newIsVip = Core::$_dbDriver->query('SELECT isVip FROM pcore_user WHERE id='.$this->user->getId());
		$newIsVip = $newIsVip[0]['isVip'];

		//期望用户新的isVip
		$this->assertEquals(1,$newIsVip);
    }

    /**
     * 测试用户原来是vip,升级用户命令,期望返回失败
     * 1. 期望数据库更新失败
     * 2. 期望原来缓存有数据,缓存没有被删除,依旧有数据
     */
    public function testUpgradeWithVipUser(){
    	
    	//查询用户旧的isVip
		$oldIsVip = Core::$_dbDriver->query('SELECT isVip FROM pcore_user WHERE id='.$this->user->getId());
		$oldIsVip = $oldIsVip[0]['isVip'];

		//期望旧的isVip字段为true
		$this->assertEquals(1,$oldIsVip);

		//初始化命令
		$command = Core::$_container->make('User\Command\UpgradeUserCommand',['user'=>$this->user]);
		//执行命令
		$result = $command->execute();

		//期望命令返回失败
		$this->assertFalse($result);

		//确认缓存没有删除
		$this->assertEquals('test',$this->userCacheLayer->get($this->user->getId()));

		//确认数据库更新失败
		$newIsVip = Core::$_dbDriver->query('SELECT isVip FROM pcore_user WHERE id='.$this->user->getId());
		$newIsVip = $newIsVip[0]['isVip'];

		//期望isVip字段还是false
		$this->assertEquals(1,$newIsVip);
    }
}