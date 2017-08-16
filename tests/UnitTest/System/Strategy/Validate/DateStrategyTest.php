<?php
//typeRule 测试日期是否合法
namespace System\Strategy\Validate;

use tests\GenericTestCase;

class DateStrategyTest extends GenericTestCase
{
    private $strategy;

    public function setUp()
    {
        $this->strategy = new DateStrategy();
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
    public function testNotValidateDate()
    {
        $result = $this->strategy->validate('date');
        $this->assertFalse($result);
    }
    
    public function testValidateDate()
    {
        $result = $this->strategy->validate('2017-08-16 12:01');
        $this->assertTrue($result);
    }
//min 测试是否大于等于给定日期
    //日期格式需要兼容
//max 测试是否小于等于给定日期
    //日期格式需要兼容
}
