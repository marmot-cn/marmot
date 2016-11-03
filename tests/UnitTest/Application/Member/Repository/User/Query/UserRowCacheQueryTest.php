<?php
namespace Member\Repository\User\Query;

use tests\GenericTestCase;
use Marmot\Core;
  
/**
 * Member\Repository\User\Query\UserContentRowCacheQuery.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */
class UserRowCacheQueryTest extends GenericTestCase
{

    private $stub;
    private $tablepre = 'pcore_';

    public function setUp()
    {
        $this->stub = Core::$container->get('Member\Repository\User\Query\UserRowCacheQuery');
    }

    /**
     * 测试该文件是否正确的继承RowCacheQuery类
     */
    public function testUserRowCacheQueryCorrectInstanceExtendsRowCacheQuery()
    {
        $this->assertInstanceof('System\Query\RowCacheQuery', $this->stub);
    }

    /**
     * 测试该文件是否正确的初始化primaryKey,并且数据库存在该字段
     */
    public function testCorrectPrimaryKey()
    {
        $key = $this->getPrivateProperty('Member\Repository\User\Query\UserRowCacheQuery', 'primaryKey');

        //判断primaryKey赋值设想一致
        $this->assertEquals('user_id', $key->getValue($this->stub));
        //检查表是否有该字段
        $results = Core::$dbDriver->query(
            'SHOW COLUMNS FROM `'.$this->tablepre.'user` LIKE \''.$key->getValue($this->stub).'\''
        );
        $this->assertNotEmpty($results);//期望检索出表名
    }

    /**
     * 测试是否cache层赋值正确
     */
    public function testCorrectCacheLayer()
    {
        $cacheLayer = $this->getPrivateProperty(
            'Member\Repository\User\Query\UserRowCacheQuery',
            'cacheLayer'
        );

        $this->assertInstanceof('Member\Persistence\UserCache', $cacheLayer->getValue($this->stub));
    }

    /**
     * 测试是否db层赋值正确
     */
    public function testCorrectDbLayer()
    {
        $dbLayer = $this->getPrivateProperty('Member\Repository\User\Query\UserRowCacheQuery', 'dbLayer');

        $this->assertInstanceof('Member\Persistence\UserDb', $dbLayer->getValue($this->stub));
    }
}
