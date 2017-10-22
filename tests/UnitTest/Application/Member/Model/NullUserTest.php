<?php
namespace Member\Model;

use tests\GenericTestCase;

class NullUserTest extends GenericTestCase
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

    public function testResouceNotExist()
    {
    }
}
