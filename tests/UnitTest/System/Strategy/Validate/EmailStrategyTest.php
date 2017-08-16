<?php
namespace System\Strategy\Validate;

use tests\GenericTestCase;

class EmailStrategyTest extends GenericTestCase
{
    private $strategy;

    public function setUp()
    {
        $this->strategy = new EmailStrategy();
    }

    public function tearDown()
    {
        unset($this->strategy);
    }

    public function testImplementsIValidateStrategy()
    {
        $this->assertInstanceOf('System\Interfaces\IValidateStrategy', $this->strategy);
    }
    
    //typeRule
    public function testNotValidateEmail()
    {
        $result = $this->strategy->validate('email');
        $this->assertFalse($result);
    }
    
    public function testValidateEmail()
    {
        $result = $this->strategy->validate('41893204@qq.com');
        $this->assertTrue($result);
    }
}
