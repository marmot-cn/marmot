<?php
namespace System\Query;

/**
 * System\Query\RowCacheQuery.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */

class RowCacheQueryTest extends GenericTestsDatabaseTestCase
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
        Core::$_cacheDriver->flushAll();
        parent::tearDown();
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
}
