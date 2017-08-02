<?php
namespace Member\Repository\User;

use tests\GenericTestsDatabaseTestCase;
use Marmot\Core;
use Member\Model\User;
use Member\Utils\ObjectGenerate;
use Member\Utils\UserUtils;

/**
 * Member/Repository/User/UserRepository.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */
class UserRepositoryTest extends GenericTestsDatabaseTestCase
{

    use UserUtils;

    public $fixtures = array(
        'pcore_user',
    );

    private $stub;

    public function setUp()
    {
        $this->stub = Core::$container->get('Member\Repository\User\UserRepository');

        parent::setUp();
    }

    public function tearDown()
    {
        Core::$cacheDriver->flushAll();
        parent::tearDown();
    }
    /**
     * 测试仓库构建
     */
    public function testUserRepositoryConstructor()
    {

        //测试RowCacheQuery构造成功
        $userRowCacheQuery = $this->getPrivateProperty(
            'Member\Repository\User\UserRepository',
            'userRowCacheQuery'
        );
        $this->assertInstanceOf(
            'Member\Repository\User\Query\UserRowCacheQuery',
            $userRowCacheQuery->getValue($this->stub)
        );
    }

    /**
     * 测试仓库add
     */
    public function testRepositoryAdd()
    {
        $user = ObjectGenerate::generateUser();
        $result = $this->stub->add($user);
        
        //期望返回true
        $this->assertTrue($result);

        //确认主键id赋值成功
        $this->assertGreaterThan(0, $user->getId());

        $expectedList = array();
        //查询数据库,确认数据插入成功
        $expectedList = Core::$dbDriver->query('SELECT * FROM pcore_user WHERE user_id='.$user->getId());
        $expectedList = $expectedList[0];

        $this->compareArrayAndObject($expectedList, $user);
    }

    public function testRepositoryUpdateDuplicate()
    {
        $user = ObjectGenerate::generateUser();
        $result = $this->stub->add($user);
        $this->assertTrue($result);

        $this->assertFalse($this->stub->update($user));
    }
    /**
     * 测试仓库save
     */
    public function testRepositoryUpdate()
    {
        $faker = \Faker\Factory::create('zh_CN');
        $faker->seed(1001);//设置seed,放置和生成数据相同

        $testUserId = 1;
        //查询旧数据,确认修改前状态
        $oldArray = Core::$dbDriver->query('SELECT * FROM pcore_user WHERE user_id ='.$testUserId);
        $this->assertNotEmpty($oldArray);//确认我们已经构建数据
        $oldArray = $oldArray[0];

        $user = new User($testUserId);

        $user->setCellPhone($faker->phoneNumber);
        $user->setPassword(md5($faker->password));
        $user->setSalt($faker->bothify('##??'));

        $user->setNickName($faker->userName);
        $user->setUserName($faker->userName);

        //随机生成新的status,剔除旧的status
        $statusArray = array(User::STATUS_NORMAL, User::STATUS_DELETE);
        $key = array_search($oldArray['status'], $statusArray);
        unset($statusArray[$key]);
        $user->setStatus($faker->randomElement($statusArray));

        //所有的时间+1,这样和以前不同
        $user->setCreateTime($oldArray['create_time'] + 1);
        $user->setUpdateTime($oldArray['update_time'] + 1);
        $user->setStatusTime($oldArray['status_time'] + 1);

        //确认旧数据和新数据不一致
        $this->assertEquals($oldArray['user_id'], $user->getId());
        $this->assertNotEquals($oldArray['cellphone'], $user->getCellPhone());
        $this->assertNotEquals($oldArray['create_time'], $user->getCreateTime());
        $this->assertNotEquals($oldArray['update_time'], $user->getUpdateTime());
        $this->assertNotEquals($oldArray['status_time'], $user->getStatusTime());
        $this->assertNotEquals($oldArray['status'], $user->getStatus());
        $this->assertNotEquals($oldArray['password'], $user->getPassword());
        $this->assertNotEquals($oldArray['salt'], $user->getSalt());
        $this->assertNotEquals($oldArray['nick_name'], $user->getNickName());
        $this->assertNotEquals($oldArray['user_name'], $user->getUserName());
        $this->assertNotEquals($oldArray['real_name'], $user->getRealName());

        $result = $this->stub->update($user);
        //期望返回true
        $this->assertTrue($result);

         //查询数据库,确认数据修改成功
        $expectedList = Core::$dbDriver->query('SELECT * FROM pcore_user WHERE user_id='.$testUserId);
        $expectedList = $expectedList[0];

        $this->compareArrayAndObject($expectedList, $user);
    }

    /**
     * 测试仓库获取单独数据
     */
    public function testRepositoryGetOne()
    {
        
        //测试询价id
        $testId = 1;

        //期待数组
        $expectedList = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE user_id='.$testId
        );
        $expectedList = $expectedList[0];

        $user = $this->stub->getOne($testId);

        $this->assertInstanceOf('Member\Model\User', $user);

        $this->compareArrayAndObject($expectedList, $user);
    }

    /**
     * 测试仓库获取批量数据
     */
    public function testRepositoryGetList()
    {
        
        //测试询价id
        $testIds = array(1, 2, 3);

        $expectedListList = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE user_id IN ('.implode(',', $testIds).')'
        );
        
        $objectList = $this->stub->getList($testIds);
  
        foreach ($expectedListList as $key => $expectedList) {
            $this->compareArrayAndObject($expectedList, $objectList[$key]);
        }
    }

    //testFilter
    public function testRepositoryFilterCellPhone()
    {
        $stub = $this->getMockBuilder(UserRepository::class)
            ->setMethods(['getList'])
            ->getMock();
        $stub->method('getList')
            ->will($this->returnArgument(0));

        //手机号是唯一的
        $expectedList = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE user_id =1'
        );

        $ids = array();
        foreach ($expectedList as $each) {
            $ids[] = $each['user_id'];
        }

        list($userList, $count) = $stub->filter(array('cellPhone'=>$expectedList[0]['cellphone']));
        $this->assertEquals(1, $count);
        $this->assertEquals($ids, $userList);
    }

    public function testRepositoryFilterPassword()
    {
        $stub = $this->getMockBuilder(UserRepository::class)
            ->setMethods(['getList'])
            ->getMock();
        $stub->method('getList')
            ->will($this->returnArgument(0));

        //添加一个用户
        $faker = \Faker\Factory::create('zh_CN');
        $user = ObjectGenerate::generateUser(0, 0, array('password'=>$faker->password));
        $this->stub->add($user);

        $expectedList = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE password = \''.$user->getPassword().'\''
        );

        $ids = array();
        foreach ($expectedList as $each) {
            $ids[] = $each['user_id'];
        }

        list($userList, $count) = $stub->filter(array('password'=>$user->getPassword()));
        $this->assertEquals(sizeof($expectedList), $count);

        $this->assertEquals($ids, $userList);
    }

    public function testRepositoryFilterStatus()
    {
        $stub = $this->getMockBuilder(UserRepository::class)
            ->setMethods(['getList'])
            ->getMock();
        $stub->method('getList')
            ->will($this->returnArgument(0));

        $expectedList = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE status = '.User::STATUS_DELETE
        );

        $ids = array();
        foreach ($expectedList as $each) {
            $ids[] = $each['user_id'];
        }
        
        //确认检索出来的又数据
        $this->assertGreaterThan(0, sizeof($expectedList));

        list($userList, $count) = $stub->filter(array('status'=>User::STATUS_DELETE));
        $this->assertEquals(sizeof($expectedList), $count);

        $this->assertEquals($ids, $userList);
    }

    public function testRepositorySortIdDesc()
    {
        $stub = $this->getMockBuilder(UserRepository::class)
            ->setMethods(['getList'])
            ->getMock();
        $stub->method('getList')
            ->will($this->returnArgument(0));

        $expectedList = Core::$dbDriver->query(
            'SELECT * FROM pcore_user ORDER BY user_id DESC'
        );

        $ids = array();
        foreach ($expectedList as $each) {
            $ids[] = $each['user_id'];
        }

        list($userList, $count) = $stub->filter(array(), array('id'=>-1));

        $this->assertEquals(sizeof($ids), $count);
        $this->assertEquals($ids, $userList);
    }

    public function testRepositorySortIdAsc()
    {
        $stub = $this->getMockBuilder(UserRepository::class)
            ->setMethods(['getList'])
            ->getMock();
        $stub->method('getList')
            ->will($this->returnArgument(0));

        $expectedList = Core::$dbDriver->query(
            'SELECT * FROM pcore_user ORDER BY user_id ASC'
        );

        $ids = array();
        foreach ($expectedList as $each) {
            $ids[] = $each['user_id'];
        }

        list($userList, $count) = $stub->filter(array(), array('id'=>1));

        $this->assertEquals(sizeof($ids), $count);
        $this->assertEquals($ids, $userList);
    }

    /**
     * all condition
     */
    public function testRepositoryFilter()
    {
        $stub = $this->getMockBuilder(UserRepository::class)
            ->setMethods(['getList'])
            ->getMock();
        $stub->method('getList')
            ->will($this->returnArgument(0));
        
        //添加一个用户,方便于我们检索
        $faker = \Faker\Factory::create('zh_CN');
        $user = ObjectGenerate::generateUser(0, 0, array(
            'password'=>$faker->password,
            'status'=>User::STATUS_NORMAL,
            'cellPhone'=>$faker->phoneNumber
            ));
        $this->stub->add($user);

        $expectedList = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE status = '.User::STATUS_NORMAL.
            ' AND cellphone = \''.$user->getCellPhone().
            '\' AND password=\''.$user->getPassword().'\''.
            ' ORDER BY user_id DESC'
        );

        $ids = array();
        foreach ($expectedList as $each) {
            $ids[] = $each['user_id'];
        }

        //确认检索出来的又数据
        $this->assertGreaterThan(0, sizeof($expectedList));

        list($userList, $count) = $stub->filter(
            array(
                'status'=>User::STATUS_NORMAL,
                'password'=>$user->getPassword(),
                'cellPhone'=>$user->getCellPhone()
            ),
            array(
                'id'=>-1
            )
        );
        $this->assertEquals(sizeof($expectedList), $count);
        
        $this->assertEquals($ids, $userList);
    }
}
