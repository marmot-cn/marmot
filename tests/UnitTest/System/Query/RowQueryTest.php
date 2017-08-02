<?php
namespace System\Query;

use tests;
use Marmot\Core;

/**
 * System\Query\RowQuery.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160301
 */

class RowQueryTest extends tests\GenericTestsDatabaseTestCase
{

    public $fixtures = array('pcore_system_test_a','pcore_system_test_b');

    private $dbStub;

    private $rowQuery;

    private $primaryKey = 'id';
    private $table = 'system_test_a';

    public function setUp()
    {

        $this->dbStub = $this->getMockBuilder('System\Classes\Db')
              ->setConstructorArgs(array($this->table))
              ->getMockForAbstractClass();

        $this->rowQuery = $this->getMockBuilder('System\Query\RowQuery')
                              ->setConstructorArgs(array($this->primaryKey,$this->dbStub))
                              ->getMockForAbstractClass();

        parent::setUp();
    }

    public function tearDown()
    {
        unset($this->dbStub);
        unset($this->rowQuery);
        //清空缓存数据
        Core::$cacheDriver->flushAll();
        parent::tearDown();
    }

    /**
     * 测试 getPrimaryKey() 方法
     */
    public function testRowQueryGetPrimaryKey()
    {
        $this->assertEquals('id', $this->rowQuery->getPrimaryKey());
    }

    /**
     * 测试 add() 方法
     * $lasetInsertId 方法为 false
     */
    public function testRowQueryAddWithoutLastInsertId()
    {
        
        $lastId = Core::$dbDriver->query('SELECT id FROM pcore_system_test_a ORDER BY id DESC LIMIT 1');
        $lastId = $lastId[0]['id'];

        $result = $this->rowQuery->add(
            array(
                'title'=>'titleA4',
                'user'=>'userA4',
            ),
            false
        );

        //测试影响了一行
        $this->assertEquals(1, $result);

        //获取新添加的数据,检查是否添加成功
        //在旧的lastId+1
        $lastId++;

        $expectArray = Core::$dbDriver->query('SELECT * FROM pcore_system_test_a WHERE id='.$lastId);
        $expectArray = $expectArray[0];

        $this->assertEquals($expectArray['id'], $lastId);
        $this->assertEquals($expectArray['title'], 'titleA4');
        $this->assertEquals($expectArray['user'], 'userA4');
    }

    /**
     * 测试 add() 方法
     * $lasetInsertId 方法为 true
     */
    public function testRowQueryAddWithLastInsertId()
    {

        $lastId = Core::$dbDriver->query('SELECT id FROM pcore_system_test_a ORDER BY id DESC LIMIT 1');
        $lastId = $lastId[0]['id'];

        $result = $this->rowQuery->add(
            array(
                'title'=>'titleA4',
                'user'=>'userA4',
            ),
            true
        );

        $lastId++;
        //测试影响了一行
        $this->assertEquals($lastId, $result);

        //获取新添加的数据,检查是否添加成功
        //在旧的lastId+1
        
        $expectArray = Core::$dbDriver->query('SELECT * FROM pcore_system_test_a WHERE id='.$lastId);
        $expectArray = $expectArray[0];

        $this->assertEquals($expectArray['id'], $lastId);
        $this->assertEquals($expectArray['title'], 'titleA4');
        $this->assertEquals($expectArray['user'], 'userA4');
    }

    /**
     * 测试 update() 方法
     */
    public function testRowQueryUpdate()
    {
        //生成缓存数据
        $testId = 1;
        $oldArray = $this->rowQuery->getOne($testId);

        $updateArray = array(
                            'title'=>'titleA4',
                            'user'=>'userA4'
                            );

        $result = $this->rowQuery->update($updateArray, array('id'=>$testId));

        $this->assertTrue($result);

        $expectArray = Core::$dbDriver->query('SELECT * FROM pcore_system_test_a WHERE id='.$testId);
        $expectArray = $expectArray[0];

        $this->assertEquals($expectArray['id'], 1);
        $this->assertEquals($expectArray['title'], $updateArray['title']);
        $this->assertEquals($expectArray['user'], $updateArray['user']);
    }

    /**
     * 测试 update() 方法
     * 更新相同数据,返回false
     */
    public function testRowQueryUpdateSameData()
    {
        //生成缓存数据
        $testId = 1;
        $oldArray = $this->rowQuery->getOne($testId);

        $updateArray = array(
                            'title'=>$oldArray['title'],
                            'user'=>$oldArray['user'],
                            );

        $result = $this->rowQuery->update($updateArray, array('id'=>$testId));

        $this->assertFalse($result);
    }

    //通过rowCache读取数据,数据库有数据,测试返回数据成功,且缓存已经被正确赋值
    public function testRowQueryGetOne()
    {

        $testId = 1;
        //获取第一条数据
        $expectArray = $this->dbStub->select($this->primaryKey.'='.$testId);
        $expectArray = $expectArray[0];

        //用RowQuery获取第一条数据
        $rowQuerResut = $this->rowQuery->getOne($testId);

        //确认返回数据正确
        $this->assertEquals($expectArray, $rowQuerResut);
    }

    /**
     * 测试 getOne() 不存在的数据
     */
    public function testRowCacheQueryGetOneNotExitId()
    {
        //查询最大id
        $lastId = Core::$dbDriver->query('SELECT id FROM pcore_system_test_a ORDER BY id DESC LIMIT 1');
        $lastId = $lastId[0]['id'];

        //最大id+1, 并确认这个id对应的数据部存在
        $lastId++;

        $expectArray = Core::$dbDriver->query('SELECT * FROM pcore_system_test_a WHERE id='.$lastId);
        $this->assertEmpty($expectArray);

        //测试getOne,查询不存在的id,返回false
        $result = $this->rowQuery->getOne($lastId);
        $this->assertFalse($result);
    }
    
    public function testRowCacheQueryGetList()
    {
        
        $testIds = array(1,2);//id数组

        //获取多条数据
        $dbResults = $this->dbStub->select($this->primaryKey .' IN ('.implode(',', $testIds).')');

        //用RowQuery获取第多条数据
        $rowQuerResuts = $this->rowQuery->getList($testIds);

        //确认返回数据正确
        $this->assertEquals($dbResults, $rowQuerResuts);
    }

    /**
     * 测试 getList 方法
     * 传递空的数组, 期望返回false
     */
    public function testRowQueryGetListWithEmptyIds()
    {
        $testIds = array();

        $result = $this->rowQuery->getList($testIds);

        $this->assertFalse($result);
    }
}
