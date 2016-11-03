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

        $expectedArray = array();
        //查询数据库,确认数据插入成功
        $expectedArray = Core::$dbDriver->query('SELECT * FROM pcore_user WHERE user_id='.$user->getId());
        $expectedArray = $expectedArray[0];

        $this->compareArrayAndObject($expectedArray, $user);
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
        $statusArray = array(STATUS_NORMAL, STATUS_DELETE);
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
        $expectedArray = Core::$dbDriver->query('SELECT * FROM pcore_user WHERE user_id='.$testUserId);
        $expectedArray = $expectedArray[0];

        $this->compareArrayAndObject($expectedArray, $user);
    }

    /**
     * 测试仓库获取单独数据
     */
    public function testRepositoryGetOne()
    {
        
        //测试询价id
        $testId = 1;

        //期待数组
        $expectedArray = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE user_id='.$testId
        );
        $expectedArray = $expectedArray[0];

        $user = $this->stub->getOne($testId);

        $this->assertInstanceOf('Member\Model\User', $user);

        $this->compareArrayAndObject($expectedArray, $user);
    }

    /**
     * 测试仓库获取批量数据
     */
    public function testRepositoryGetList()
    {
        
        //测试询价id
        $testIds = array(1, 2, 3);

        $expectedArrayList = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE user_id IN ('.implode(',', $testIds).')'
        );
        
        $objectList = $this->stub->getList($testIds);
  
        foreach ($expectedArrayList as $key => $expectedArray) {
            $this->compareArrayAndObject($expectedArray, $objectList[$key]);
        }
    }

    //testFilter
    public function testRepositoryFilterCellPhone()
    {
        //手机号是唯一的
        $expectedArray = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE user_id =1'
        );
        $expectedArray = $expectedArray[0];

        list($userList, $count) = $this->stub->filter(array('cellPhone'=>$expectedArray['cellphone']));
        $this->assertEquals(1, $count);
        $this->compareArrayAndObject($expectedArray, $userList[0]);
    }

    public function testRepositoryFilterPassword()
    {
        //添加一个用户
        $faker = \Faker\Factory::create('zh_CN');
        $user = ObjectGenerate::generateUser(0, 0, array('password'=>$faker->password));
        $this->stub->add($user);

        $expectedList = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE password = \''.$user->getPassword().'\''
        );

        list($userList, $count) = $this->stub->filter(array('password'=>$user->getPassword()));
        $this->assertEquals(sizeof($expectedList), $count);

        foreach ($expectedList as $key => $expected) {
            $this->compareArrayAndObject($expected, $userList[$key]);
        }
    }

    public function testRepositoryFilterStatus()
    {
        $expectedList = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE status = '.STATUS_DELETE
        );

        //确认检索出来的又数据
        $this->assertGreaterThan(0, sizeof($expectedList));

        list($userList, $count) = $this->stub->filter(array('status'=>STATUS_DELETE));
        $this->assertEquals(sizeof($expectedList), $count);

        foreach ($expectedList as $key => $expected) {
            $this->compareArrayAndObject($expected, $userList[$key]);
        }
    }

    /**
     * all condition
     */
    public function testRepositoryFilter()
    {
        //添加一个用户,方便于我们检索
        $faker = \Faker\Factory::create('zh_CN');
        $user = ObjectGenerate::generateUser(0, 0, array(
            'password'=>$faker->password,
            'status'=>STATUS_NORMAL,
            'cellPhone'=>$faker->phoneNumber
            ));
        $this->stub->add($user);

        $expectedList = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE status = '.STATUS_NORMAL.
            ' AND cellphone = \''.$user->getCellPhone().
            '\' AND password=\''.$user->getPassword().'\''
        );

        //确认检索出来的又数据
        $this->assertGreaterThan(0, sizeof($expectedList));

        list($userList, $count) = $this->stub->filter(array(
                'status'=>STATUS_NORMAL,
                'password'=>$user->getPassword(),
                'cellPhone'=>$user->getCellPhone()
            ));
        $this->assertEquals(sizeof($expectedList), $count);

        foreach ($expectedList as $key => $expected) {
            $this->compareArrayAndObject($expected, $userList[$key]);
        }
    }
}
