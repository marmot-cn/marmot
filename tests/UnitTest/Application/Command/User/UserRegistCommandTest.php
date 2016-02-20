<?php
/**
 * 测试用户注册命令,我们传参一个新的用户期望得到如下情况:
 * 1. 命令返回true
 * 2. 数据插入新的用户数据
 * 3. 用户对象复制新的uid
 */
class UserRegistCommandTest extends GenericTestsDatabaseTestCase{
	
	public $fixtures = array('pcore_user');

	private $user;

	public function testPrivateUserProperty(){
		//初始化User
		$userName = 'chloroplast';
		$password = '111111';

		$this->user = new Model\User\User();
		$this->user->setUserName($userName);
		$this->user->setPassword($password);

		$command = Core::$_container->make('Command\User\UserRegistCommand',['user'=>$this->user]);

		$property = $this->getPrivateProperty('Command\User\UserRegistCommand', 'user');

		$this->assertEquals($property->getValue($command)->getUserName(), $userName);
		$this->assertNotEmpty($property->getValue($command)->getPassword());//密码不为空
		$this->assertNotEquals($property->getValue($command)->getPassword(), $password);//密码加密过,所以不同
	}

	public function testUserRegist(){

		//初始化User
		$userName = 'chloroplast';
		$password = '111111';

		$this->user = new Model\User\User();
		$this->user->setUserName($userName);
		$this->user->setPassword($password);
		//检索最后用户id
		$lastUserInfo = Core::$_dbDriver->query('SELECT MAX(uid) as uid FROM pcore_user');
		$lastUid = $lastUserInfo[0]['uid'];

		//command
		$command = Core::$_container->make('Command\User\UserRegistCommand',['user'=>$this->user]);
		$result = $command->execute();//注册
		$this->assertTrue($result);

		//确认新注册用户的uid赋值成功,要比最后用户的uid大(自增)
		$this->assertGreaterThan($lastUid,$this->user->getUid());
		//检索数据,查询新插入的数据
		$userInfo = Core::$_dbDriver->query('SELECT * FROM pcore_user WHERE uid='.$this->user->getUid());
		$userInfo = $userInfo[0];
		$this->assertEquals($this->user->getUsername(),$userInfo['userName']);
		$this->assertEquals($this->user->getPassword(),$userInfo['password']);

		//测试缓存
		$userInfo = Core::$_cacheDriver->fetch('user_'.$this->user->getUid());
		$this->assertEquals($this->user->getUsername(),$userInfo['userName']);
	}

	/**
	 * 测试注册后用户名如果为空,命令返回false;
	 */
	public function testUserRegistWithoutUserName(){

		//初始化User
		$userName = '';
		$password = '111111';

		$this->user = new Model\User\User();
		$this->user->setUserName($userName);
		$this->user->setPassword($password);

		//查询用户总数
		$oldUserCounts = Core::$_dbDriver->query('SELECT COUNT(*) as count FROM pcore_user');

		//command
		$command = Core::$_container->make('Command\User\UserRegistCommand',['user'=>$this->user]);
		$result = $command->execute();//注册

		$this->assertFalse($result);

		//再次查询用户总数
		$newUserCounts = Core::$_dbDriver->query('SELECT COUNT(*) as count FROM pcore_user');
		
		$this->assertEquals($oldUserCounts,$newUserCounts);
	}
}