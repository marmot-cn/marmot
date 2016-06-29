<?php
namespace System\Query;

/**
 * System\Query\RowQuery.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160301
 */

class RowQueryTest extends GenericTestsDatabaseTestCase
{

    public $fixtures = array('pcore_system_test_a','pcore_system_test_b');

    private $dbStub;

    private $rowCacheQuery;

    private $primaryKey = 'id';
    private $table = 'system_test_a';
    private $cacheKeyPrefix = 'pcore';

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
        Core::$_cacheDriver->flushAll();
        parent::tearDown();
    }
    //通过rowCache读取数据,数据库有数据,测试返回数据成功,且缓存已经被正确赋值
    public function testRowQueryGetOne()
    {

        $testId = 1;
        //获取第一条数据
        $dbResult = $this->dbStub->select($this->primaryKey.'='.$testId);

        //用RowQuery获取第一条数据
        $rowQuerResut = $this->rowQuery->getOne($testId);

        //确认返回数据正确
        $this->assertEquals($dbResult, $rowQuerResut);
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
}
