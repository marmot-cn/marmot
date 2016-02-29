<?php
/**
 * User/Command/UpdateLastScoreUserCommand.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */

class UpdateLastScoreUserCommandTest extends GenericTestsDatabaseTestCase{

	public $fixtures = array('pcore_user');

	private $user;//用户对象

	private $userCacheLayer;

	public function setUp(){    
		//初始化用户
		$this->user = new User\Model\User();
		$this->user->setId(1);
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
	 * 测试一个更新用户新的分数,期望返回成功
	 * 1. 期望数据库更新成功
	 * 2. 期望原来缓存有数据,缓存被删除,缓存为空
	 */
	public function testUpdateLastScoreWithDifferentLastScore(){
		//查询用户旧的分数
		$lastScore = Core::$_dbDriver->query('SELECT lastScore FROM pcore_user WHERE id='.$this->user->getId());
		$lastScore = $lastScore[0]['lastScore'];

		//赋值新的分数
		$newScore = $lastScore + 1;
		$this->user->setLastScore($newScore);

		//初始化命令
		$command = Core::$_container->make('User\Command\UpdateLastScoreUserCommand',['user'=>$this->user]);
		//执行命令
		$result = $command->execute();

		//期望命令返回成功
		$this->assertTrue($result);

		//确认缓存删除成功
		$this->assertEmpty($this->userCacheLayer->get($this->user->getId()));

		//确认数据库更新成功
		$newLastScore = Core::$_dbDriver->query('SELECT lastScore FROM pcore_user WHERE id='.$this->user->getId());
		$newLastScore = $newLastScore[0]['lastScore'];

		$this->assertEquals($newLastScore,$newScore);
	}

	/**
	 * 测试重复重新用户相同的分数,期望返回失败
	 * 1. 期望数据库更新失败
	 * 2. 期望原来缓存有数据,缓存没有被删除,依旧有数据
	 */
	public function testUpdateLastScoreWithSameLastScore(){
		//查询用户旧的分数
		$lastScore = Core::$_dbDriver->query('SELECT lastScore FROM pcore_user WHERE id='.$this->user->getId());
		$lastScore = $lastScore[0]['lastScore'];

		$this->user->setLastScore($lastScore);
		//初始化命令
		$command = Core::$_container->make('User\Command\UpdateLastScoreUserCommand',['user'=>$this->user]);
		//执行命令
		$result = $command->execute();

		//期望命令返回失败
		$this->assertFalse($result);

		//确认缓存没有删除
		$this->assertEquals('test',$this->userCacheLayer->get($this->user->getId()));

		//确认数据库更新失败
		$newLastScore = Core::$_dbDriver->query('SELECT lastScore FROM pcore_user WHERE id='.$this->user->getId());
		$newLastScore = $newLastScore[0]['lastScore'];

		$this->assertEquals($newLastScore,$lastScore);
	}
}
// ?>