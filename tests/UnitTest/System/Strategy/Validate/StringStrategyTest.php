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

    //min 最小字节, 需要考虑字符串传参utf-8, 使用mb_strlen
    //max 最大字节, mb_len
    //between 字节数
    //range in_array(一堆字符串)
}
