<?php
namespace System\Query;

use tests;
use Marmot\Core;

/**
 * System\Query\RowCacheQuery.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */

class RowCacheQueryTest extends tests\GenericTestsDatabaseTestCase
{

    public $fixtures = array('pcore_system_test_a','pcore_system_test_b');

    private $dbStub;

    private $cacheStub;

    private $rowCacheQuery;

    private $primaryKey = 'id';
    private $table = 'system_test_a';
    private $cacheKeyPrefix = 'pcore';

    public function setUp()
    {
        $this->cacheStub = $this->getMockBuilder('System\Classes\Cache')
                  ->setConstructorArgs(array($this->cacheKeyPrefix))
                  ->getMockForAbstractClass();

        $this->dbStub = $this->getMockBuilder('System\Classes\Db')
              ->setConstructorArgs(array($this->table))
              ->getMockForAbstractClass();

        $this->rowCacheQuery = $this->getMockBuilder('System\Query\RowCacheQuery')
                              ->setConstructorArgs(array($this->primaryKey,$this->cacheStub,$this->dbStub))
                              ->getMockForAbstractClass();

        parent::setUp();
    }

    public function tearDown()
    {
        unset($this->dbStub);
        unset($this->cacheStub);
        unset($this->rowQuery);
        //清空缓存数据
        Core::$cacheDriver->flushAll();
        parent::tearDown();
    }

    /**
     * 测试 getPrimaryKey() 方法
     */
    public function testRowCacheQueryGetPrimaryKey()
    {
        $this->assertEquals('id', $this->rowCacheQuery->getPrimaryKey());
    }

    /**
     * 测试 add() 方法
     * $lasetInsertId 方法为 false
     */
    public function testRowCacheQueryAddWithoutLastInsertId()
    {
        
        $lastId = Core::$dbDriver->query('SELECT id FROM pcore_system_test_a ORDER BY id DESC LIMIT 1');
        $lastId = $lastId[0]['id'];

        $result = $this->rowCacheQuery->add(
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
    public function testRowCacheQueryAddWithLastInsertId()
    {

        $lastId = Core::$dbDriver->query('SELECT id FROM pcore_system_test_a ORDER BY id DESC LIMIT 1');
        $lastId = $lastId[0]['id'];

        $result = $this->rowCacheQuery->add(
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
    public function testRowCacheQueryUpdate()
    {
        //生成缓存数据
        $testId = 1;
        $oldArray = $this->rowCacheQuery->getOne($testId);

        //测试缓存数据不为空
        $this->assertNotEmpty($this->cacheStub->get($testId));

        $updateArray = array(
                            'title'=>'titleA4',
                            'user'=>'userA4'
                            );

        $result = $this->rowCacheQuery->update($updateArray, array('id'=>$testId));

        $this->assertTrue($result);

        $expectArray = Core::$dbDriver->query('SELECT * FROM pcore_system_test_a WHERE id='.$testId);
        $expectArray = $expectArray[0];

        $this->assertEquals($expectArray['id'], 1);
        $this->assertEquals($expectArray['title'], $updateArray['title']);
        $this->assertEquals($expectArray['user'], $updateArray['user']);

        //测试缓存数据被清空
        $this->assertEmpty($this->cacheStub->get($testId));
    }

    /**
     * 测试 update() 方法
     * 更新相同数据,返回false
     */
    public function testRowCacheQueryUpdateSameData()
    {
        //生成缓存数据
        $testId = 1;
        $oldArray = $this->rowCacheQuery->getOne($testId);

        //测试缓存数据不为空
        $this->assertNotEmpty($this->cacheStub->get($testId));

        $updateArray = array(
                            'title'=>$oldArray['title'],
                            'user'=>$oldArray['user'],
                            );

        $result = $this->rowCacheQuery->update($updateArray, array('id'=>$testId));

        $this->assertFalse($result);
    }

    //通过rowCache读取数据,数据库有数据,测试返回数据成功,且缓存已经被正确赋值
    public function testRowCacheQueryGetOne()
    {

        $testId = 1;
        //获取第一条数据
        $dbResult = $this->dbStub->select($this->primaryKey.'='.$testId);
        $dbResult = $dbResult[0];
        
        //确认缓存一开始无数据
        $this->assertEmpty($this->cacheStub->get($testId));

        //用QueryCache获取第一条数据
        $rowCacheQuerResut = $this->rowCacheQuery->getOne($testId);

        //确认返回数据正确
        $this->assertEquals($dbResult, $rowCacheQuerResut);

        //检查内存是否有数据,期望缓存有数据
        $this->assertEquals($dbResult, $this->cacheStub->get($testId));

        //现在我们需要测试如果缓存有数据,则不在读取数据库,
        //所以我们删除缓存,测试是否可以正常获取数据
        //正常开发场景我们应该在封装的command中,变更数据后删除缓存.
        //这里这么处理只是用于测试
        
        //删除该id对应的数据
        $this->dbStub->delete(array($this->primaryKey=>$testId));

        //确认数据库数据已经没有
        $this->assertEmpty($this->dbStub->select($this->primaryKey.'='.$testId));
        //我们从缓存读取数据,检查数据是否从缓存获取,而不会路由到数据库查询
        $rowCacheQuerResut = $this->rowCacheQuery->getOne($testId);

        //确认返回数据和当初的数据一致
        $this->assertEquals($dbResult, $rowCacheQuerResut);
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
        $result = $this->rowCacheQuery->getOne($lastId);
        $this->assertFalse($result);
    }

    /**
     * 测试 getList()
     */
    public function testRowCacheQueryGetList()
    {
        
        $testIds = array(1,2);//id数组
        //获取多条数据
        $dbResults = $this->dbStub->select($this->primaryKey .' IN ('.implode(',', $testIds).')');

        //确认缓存一开始无数据
        $this->assertEmpty($this->cacheStub->get($testIds[0]));
        $this->assertEmpty($this->cacheStub->get($testIds[1]));

        //用QueryCache获取多条数据
        $rowCacheQuerResuts = $this->rowCacheQuery->getList($testIds);
        //确认返回数据正确
        $this->assertEquals($dbResults, $rowCacheQuerResuts);

        //检查内存是否有数据,期望缓存有数据
        $this->assertEquals($dbResults[0], $this->cacheStub->get($testIds[0]));
        $this->assertEquals($dbResults[1], $this->cacheStub->get($testIds[1]));

        //现在我们需要测试如果缓存有数据,则不在读取数据库,
        //所以我们删除缓存,测试是否可以正常获取数据
        //正常开发场景我们应该在封装的command中,
        //变更数据后删除缓存.
        //这里这么处理只是用于测试
        
        //删除该id对应的数据
        $this->dbStub->delete(array($this->primaryKey=>$testIds[0]));
        $this->dbStub->delete(array($this->primaryKey=>$testIds[1]));

        //确认数据库数据已经没有
        $this->assertEmpty($this->dbStub->select($this->primaryKey .' IN ('.implode(',', $testIds).')'));

        //我们从缓存读取数据,检查数据是否从缓存获取,而不会路由到数据库查询
        $rowCacheQuerResuts = $this->rowCacheQuery->getList($testIds);

        //确认返回数据和当初的数据一致
        $this->assertEquals($dbResults, $rowCacheQuerResuts);
    }

    /**
     * 测试 getList 方法
     * 传递空的数组, 期望返回false
     */
    public function testRowCacheQueryGetListWithEmptyIds()
    {
        $testIds = array();

        $result = $this->rowCacheQuery->getList($testIds);

        $this->assertFalse($result);
    }

    /**
     * 测试 trait RowQueryFindable find() && size > 0
     */
    public function testRowCacheQueryFindWithSize()
    {
        $result = $this->rowCacheQuery->find('1', 0, 2);
        $expectArray = Core::$dbDriver->query('SELECT id FROM pcore_system_test_a WHERE 1 LIMIT 0, 2');

        $this->assertEquals($result, $expectArray);
    }

    /**
     * 测试 trait RowQueryFindable find() && size = 0
     */
    public function testRowCacheQueryFindWithoutSize()
    {
        $result = $this->rowCacheQuery->find('id=1', 0, 0);
        $expectArray = Core::$dbDriver->query('SELECT id FROM pcore_system_test_a WHERE id=1');

        $this->assertEquals($result, $expectArray);
    }

    /**
     * 测试 trait RowQueryFindable count()
     */
    public function testRowCacheQueryCount()
    {
        $result = $this->rowCacheQuery->count('1');
        $expectArray = Core::$dbDriver->query('SELECT COUNT(*) as count FROM pcore_system_test_a WHERE 1');

        $this->assertEquals($result, $expectArray[0]['count']);
    }
}
