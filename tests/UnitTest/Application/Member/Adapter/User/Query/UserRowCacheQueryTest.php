<?php
namespace Member\Adapter\User\Query;

use PHPUnit\Framework\TestCase;
use Marmot\Core;

use Marmot\Framework\Interfaces\DbLayer;
use Marmot\Framework\Interfaces\CacheLayer;

class UserRowCacheQueryTest extends TestCase
{
    private $rowCacheQuery;

    private $tablepre = 'pcore_';

    public function setUp()
    {
        $this->rowCacheQuery = new class extends UserRowCacheQuery
        {
            public function getCacheLayer() : CacheLayer
            {
                return parent::getCacheLayer();
            }

            public function getDbLayer() : DbLayer
            {
                return parent::getDbLayer();
            }
        };
    }

    /**
     * 测试该文件是否正确的继承RowCacheQuery类
     */
    public function testCorrectInstanceExtendsRowCacheQuery()
    {
        $this->assertInstanceof('Marmot\Framework\Query\RowCacheQuery', $this->rowCacheQuery);
    }

    /**
     * 测试是否cache层赋值正确
     */
    public function testCorrectCacheLayer()
    {
        $this->assertInstanceof(
            'Member\Adapter\User\Query\Persistence\UserCache',
            $this->rowCacheQuery->getCacheLayer()
        );
    }

    /**
     * 测试是否db层赋值正确
     */
    public function testCorrectDbLayer()
    {
        $this->assertInstanceof(
            'Member\Adapter\User\Query\Persistence\UserDb',
            $this->rowCacheQuery->getDbLayer()
        );
    }
}
