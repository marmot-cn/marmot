<?php
namespace Member\Adapter\User\Query\Persistence;

use PHPUnit\Framework\TestCase;
use Marmot\Core;

/**
 * Member/Persistence/UserCache.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */
class UserCacheTest extends TestCase
{

    private $cache;

    public function setUp()
    {
        $this->cache = new UserCache();
    }

    /**
     * 测试该文件是否正确的继承cache类
     */
    public function testCorrectInstanceExtendsCache()
    {
        $this->assertInstanceof('Marmot\Framework\Classes\Cache', $this->cache);
    }
}
