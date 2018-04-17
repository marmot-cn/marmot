<?php
namespace Member\Model;

use PHPUnit\Framework\TestCase;

use Marmot\Core;

class NullUserTest extends TestCase
{
    private $nullUser;

    public function setUp()
    {
        $this->nullUser = new NullUser();
    }

    public function tearDown()
    {
        unset($this->nullUser);
    }

    public function testExtendsUser()
    {
        $this->assertInstanceof('Member\Model\User', $this->nullUser);
    }

    public function testImplementsNull()
    {
        $this->assertInstanceof('System\Interfaces\INull', $this->nullUser);
    }

    public function testIsNormal()
    {
        $result = $this->nullUser->isNormal();
        $this->assertFalse($result);
        $this->assertEquals(RESOURCE_NOT_EXIST, Core::getLastError()->getId());
    }
    
    public function testIsDelete()
    {
        $result = $this->nullUser->isDelete();
        $this->assertFalse($result);
        $this->assertEquals(RESOURCE_NOT_EXIST, Core::getLastError()->getId());
    }

    public function testChangePassword()
    {
        $result = $this->nullUser->changePassword('oldPassword', 'newPassword');
        $this->assertFalse($result);
        $this->assertEquals(RESOURCE_NOT_EXIST, Core::getLastError()->getId());
    }
}
