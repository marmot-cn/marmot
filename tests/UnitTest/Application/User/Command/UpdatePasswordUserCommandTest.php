<?php
/**
 * User/Command/UpdatePasswordUserCommand.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */

class UpdatePasswordUserCommandTest extends GenericTestsDatabaseTestCase{

	public $fixtures = array('pcore_user');

	private $user;

	private $userCacheLayer;

	public function setUp(){    
		//初始化user
		$this->user = new User\Model\User();
		$this->user->setId(3);//3号id用户名为密码是 123456
		$this->user->setPassword('111111');//赋值新的密码
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
     * 测试用户修改密码,生成新的salt和加密的密码和原来的数据不一致
     */
    public function testUpdatePasswordWithNewPassword(){

    	//查询旧用户的数据(密码 和 盐)
		$lastUserInfo = Core::$_dbDriver->query('SELECT password,salt FROM pcore_user WHERE id='.$this->user->getId());

    	//初始化命令
		$command = Core::$_container->make('User\Command\UpdatePasswordUserCommand',['user'=>$this->user]);
		//执行命令
		$result = $command->execute();

		//期望命令返回成功
		$this->assertTrue($result);

		//确认缓存删除成功
		$this->assertEmpty($this->userCacheLayer->get($this->user->getId()));

		//查询新用户的数据(密码 和 盐)
		$newUserInfo = Core::$_dbDriver->query('SELECT password,salt FROM pcore_user WHERE id='.$this->user->getId());

		//确认密码和盐不为空
		$this->assertNotEmpty($newUserInfo[0]['password']);
		$this->assertNotEmpty($newUserInfo[0]['salt']);

		//确认和旧的用户 密码 盐 都不一致
		$this->assertNotEquals($lastUserInfo[0]['password'],$newUserInfo[0]['password']);
		$this->assertNotEquals($lastUserInfo[0]['salt'],$newUserInfo[0]['salt']);
    }
}