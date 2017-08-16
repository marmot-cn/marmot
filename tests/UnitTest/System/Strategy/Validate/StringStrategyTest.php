<?php
namespace System\Strategy\Validate;

use tests\GenericTestCase;

class StringStrategyTest extends GenericTestCase
{
    private $strategy;

    public function setUp()
    {
        $this->strategy = new StringStrategy();
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
    public function testNotValidateString()
    {
        $result = $this->strategy->validate(array('111'));
        $this->assertFalse($result);
    }
    
    public function testValidateString()
    {
        $result = $this->strategy->validate('string');
        $this->assertTrue($result);
    }
}
