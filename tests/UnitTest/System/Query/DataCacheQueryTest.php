<?php
namespace System\Query;

use tests;
use Marmot\Core;

/**
 * System\Query\DataCacheQuery.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */
class DataCacheQueryTest extends tests\GenericTestsDatabaseTestCase
{

    private $dataCacheQuery;

    private $cacheStub;

    private $cacheKeyPrefix = 'pcore';

    public function setUp()
    {
        $this->cacheStub = $this->getMockBuilder('System\Classes\Cache')
                  ->setConstructorArgs(array($this->cacheKeyPrefix))
                  ->getMockForAbstractClass();

        $this->dataCacheQuery = $this->getMockBuilder('System\Query\DataCacheQuery')
                              ->setConstructorArgs(array($this->cacheStub))
                              ->getMockForAbstractClass();

        parent::setUp();
    }

    public function tearDown()
    {
        unset($this->cacheStub);
        unset($this->dataCacheQuery);
        //清空缓存数据
        Core::$cacheDriver->flushAll();
        parent::tearDown();
    }

    /**
     * 测试 save() 方法
     */
    public function testSave()
    {
        $testKey = 'key';
        $testData = 'value';

        $result = $this->dataCacheQuery->save($testKey, $testData);
        $this->assertTrue($result);

        $result = $this->dataCacheQuery->get($testKey);
        $this->assertEquals($testData, $result);
    }

    /**
     * 测试 del() 方法
     */
    public function testDel()
    {
        $testKey = 'key';
        $testData = 'value';

        $result = $this->dataCacheQuery->save($testKey, $testData);
        $this->assertTrue($result);

        $this->assertNotEmpty($this->dataCacheQuery->get($testKey));

        $this->dataCacheQuery->del($testKey);
        $this->assertEmpty($this->dataCacheQuery->get($testKey));
    }
}
