<?php
namespace System\Strategy\Validate;

use tests\GenericTestCase;

class FloatStrategyTest extends GenericTestCase
{
    private $strategy;

    public function setUp()
    {
        $this->strategy = new FloatStrategy();
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
    public function testNotValidateFloat()
    {
        $result = $this->strategy->validate(array(1));
        $this->assertFalse($result);
    }
    
    public function testValidateFloat()
    {
        $result = $this->strategy->validate(1.0);
        $this->assertTrue($result);
    }

    public function testValidateFloatWithIntFormat()
    {
        $result = $this->strategy->validate(1);
        $this->assertFalse($result);
    }
    
    //min
    public function testMinWithLargerNumber()
    {
        $result = $this->strategy->validate(11.0, 'min:10');
        $this->assertTrue($result);
    }

    public function testMinWithEqualNumber()
    {
        $result = $this->strategy->validate(11.0, 'min:11');
        $this->assertTrue($result);
    }

    public function testMinWiehSmallerNumber()
    {
        $result = $this->strategy->validate(9.0, 'min:11');
        $this->assertFalse($result);
    }

    //max
    public function testMaxWithLargerNumber()
    {
        $result = $this->strategy->validate(12.0, 'max:10');
        $this->assertFalse($result);
    }

    public function testMaxWithSmallerNumber()
    {
        $result = $this->strategy->validate(9.0, 'max:10');
        $this->assertTrue($result);
    }

    public function testMaxWithEqualNumber()
    {
        $result = $this->strategy->validate(9.0, 'max:9');
        $this->assertTrue($result);
    }

    //between
    public function testBetweenInRange()
    {
        $result = $this->strategy->validate(10.0, 'between:5.0,15.2');
        $this->assertTrue($result);
    }

    public function testBetweenOutOfRange()
    {
        $result = $this->strategy->validate(1.0, 'between:5.0,15.2');
        $this->assertFalse($result);

        $result = $this->strategy->validate(20.0, 'between:5.0,15.2');
        $this->assertFalse($result);
    }

    //range
    public function testRangeInRange()
    {
        $result = $this->strategy->validate(1.0, 'range:1.0,2.2,3.4');
        $this->assertTrue($result);
    }

    public function testRangeOutOfRange()
    {
        $result = $this->strategy->validate(4.0, 'range:1.0,2.2,3.4');
        $this->assertFalse($result);
    }

    //multiRule
    public function testMultiRuleInRange()
    {
        $result = $this->strategy->validate(10.0, 'min:5.0|max:15.0');
        $this->assertTrue($result);
    }

    public function testMultiRuleOutOfRange()
    {
        $result = $this->strategy->validate(2.0, 'min:5.0|max:15.0');
        $this->assertFalse($result);

        $result = $this->strategy->validate(20.0, 'min:5.0|max:15.0');
        $this->assertFalse($result);
    }
}
