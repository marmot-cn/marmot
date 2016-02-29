<?php
/**
 * User/Command/UpdateProfileUserCommand.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */

class UpdateProfileUserCommandTest extends GenericTestsDatabaseTestCase{

	public $fixtures = array('pcore_user');

	private $user;

	private $userCacheLayer;

	public function setUp(){    
		//初始化user
		$this->user = new User\Model\User();
		$this->user->setId(1);//3号用户不是vip

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
     * 更新用户信息,期望:
     * 1. 命令返回成功
     * 2. 缓存删除成功
     * 3. 数据库更新成功
     */	
    public function testUpdateProfileWithNewProfile(){

		$updateUserProfileInfo = array('avatarId'=>2,
									   'realName'=>'李四',
									   'provinceId'=>11,
									   'cityId'=>149,
									   'districtId'=>1255,
									   'colleage'=>'高新一中初中部',
									   'birthday'=>'1990-01-07',
									   'gender'=>2,
									   'subject'=>1,
									   'email'=>'15@qq.com',
									   'qq'=>'1348107675');

    	//拼接用户profile
    	$this->user->setAvatar($updateUserProfileInfo['avatarId']);
    	$this->user->setRealName($updateUserProfileInfo['realName']);
    	$this->user->getProvince()->setId($updateUserProfileInfo['provinceId']);
    	$this->user->getCity()->setId($updateUserProfileInfo['cityId']);
    	$this->user->getDistrict()->setId($updateUserProfileInfo['districtId']);
    	$this->user->setColleage($updateUserProfileInfo['colleage']);
    	$this->user->setBirthday($updateUserProfileInfo['birthday']);
    	$this->user->setGender($updateUserProfileInfo['gender']);
    	$this->user->setSubject($updateUserProfileInfo['subject']);
    	$this->user->setEmail($updateUserProfileInfo['email']);
    	$this->user->setQq($updateUserProfileInfo['qq']);

    	//初始化命令
		$command = Core::$_container->make('User\Command\UpdateProfileUserCommand',['user'=>$this->user]);
		//执行命令
		$result = $command->execute();

		//期望命令返回成功
		$this->assertTrue($result);

		//确认缓存删除成功
		$this->assertEmpty($this->userCacheLayer->get($this->user->getId()));

		//查询新用户的数据
		$newUserProfileInfo = Core::$_dbDriver->query('SELECT * FROM pcore_user WHERE id='.$this->user->getId());
		$newUserProfileInfo = $newUserProfileInfo[0];
		//确认更新后的用户profile数据一致
		$this->assertNotEquals($updateUserProfileInfo,$newUserProfileInfo);
    }

    /**
     * 重复更新用户信息(和用户旧数据一致),期望:
     * 1. 命令返回失败
     * 2. 缓存删除失败
     * 3. 数据库库更新失败
     */
    public function testUpdateProfileWithOldProfile(){

    	$oldNewProfileInfo = Core::$_dbDriver->query('SELECT * FROM pcore_user WHERE id='.$this->user->getId());
	    $oldNewProfileInfo = $oldNewProfileInfo[0];

    	//拼接用户profile
    	$this->user->setAvatar($oldNewProfileInfo['avatarId']);
    	$this->user->setRealName($oldNewProfileInfo['realName']);
    	$this->user->getProvince()->setId($oldNewProfileInfo['provinceId']);
    	$this->user->getCity()->setId($oldNewProfileInfo['cityId']);
    	$this->user->getDistrict()->setId($oldNewProfileInfo['districtId']);
    	$this->user->setColleage($oldNewProfileInfo['colleage']);
    	$this->user->setBirthday($oldNewProfileInfo['birthday']);
    	$this->user->setGender($oldNewProfileInfo['gender']);
    	$this->user->setSubject($oldNewProfileInfo['subject']);
    	$this->user->setEmail($oldNewProfileInfo['email']);
    	$this->user->setQq($oldNewProfileInfo['qq']);

    	//初始化命令
		$command = Core::$_container->make('User\Command\UpdateProfileUserCommand',['user'=>$this->user]);
		//执行命令
		$result = $command->execute();

		//期望命令返回失败
		$this->assertFalse($result);

		//确认缓存没有删除
		$this->assertEquals('test',$this->userCacheLayer->get($this->user->getId()));

		//查询新用户的数据
		$newUserProfileInfo = Core::$_dbDriver->query('SELECT * FROM pcore_user WHERE id='.$this->user->getId());
		$newUserProfileInfo = $newUserProfileInfo[0];
		//确认用户profile没有更新
		$this->assertEquals($oldNewProfileInfo,$newUserProfileInfo);
    }
}
?>