<?php
namespace Member\Adapter\User\Query\Persistence;

use PHPUnit\Framework\TestCase;
use Marmot\Core;

/**
 * Member/Persistence/UserDb.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */
class UserDbTest extends TestCase
{

    private $db;

    public function setUp()
    {
        $this->db = new UserDb();
    }

    /**
     * 测试该文件是否正确的继承Db类
     */
    public function testCorrectInstanceExtendsDb()
    {
        $this->assertInstanceof('Marmot\Framework\Classes\Db', $this->db);
    }
}
