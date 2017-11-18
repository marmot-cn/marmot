<?php
namespace Member\Adapter\User\Query\Persistence;

use tests\GenericTestCase;
use Marmot\Core;

/**
 * Member/Persistence/UserCache.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */
class UserCacheTest extends GenericTestCase
{

    private $cache;
    private $tablepre = 'pcore_';

    public function setUp()
    {
        $this->cache = new UserCache();
    }

    /**
     * 测试该文件是否正确的继承cache类
     */
    public function testCorrectInstanceExtendsCache()
    {
        $this->assertInstanceof('System\Classes\Cache', $this->cache);
    }
}
