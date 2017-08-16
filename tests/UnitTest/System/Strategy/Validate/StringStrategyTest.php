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

    //min
    public function testMinWithLargerString()
    {
        $result = $this->strategy->validate('测a12', 'min:3');
        $this->assertTrue($result);
    }

    public function testMinWithEqualString()
    {
        $result = $this->strategy->validate('测a1', 'min:3');
        $this->assertTrue($result);
    }

    public function testMinWithSmallerString()
    {
        $result = $this->strategy->validate('册1a', 'min:4');
        $this->assertFalse($result);
    }

    //max
    public function testMaxWithLargerString()
    {
        $result = $this->strategy->validate('册1aqw', 'max:4');
        $this->assertFalse($result);
    }

    public function testMaxWithSmallerString()
    {
        $result = $this->strategy->validate('册1a', 'max:5');
        $this->assertTrue($result);
    }

    public function testMaxWithEqualString()
    {
        $result = $this->strategy->validate('册1a', 'max:3');
        $this->assertTrue($result);
    }

    //between
    public function testBetweenInRange()
    {
        $result = $this->strategy->validate('册1aq', 'between:1,5');
        $this->assertTrue($result);
    }

    public function testBetweenOutOfRange()
    {
        $result = $this->strategy->validate('q册', 'between:3,8');
        $this->assertFalse($result);

        $result = $this->strategy->validate('说的问问ww2323', 'between:3,8');
        $this->assertFalse($result);
    }

    //range
    public function testRangeInRange()
    {
        $result = $this->strategy->validate('ee', 'range:ee,sss,www');
        $this->assertTrue($result);
    }

    public function testRangeOutOfRange()
    {
        $result = $this->strategy->validate('weqw', 'range:qwe,ee,ss');
        $this->assertFalse($result);
    }
    //regular
    public function testRegularInRegular()
    {
        $result = $this->strategy->validate('二喂喂喂', 'regular:/^[\x{4e00}-\x{9fa5}]+$/u');
        $this->assertTrue($result);
    }

    public function testRegularOutOfRegular()
    {
        $result = $this->strategy->validate('71444', 'regular:/^1[1-9][0-9]{9}$/');
        $this->assertFalse($result);
    }

    //min 最小字节, 需要考虑字符串传参utf-8, 使用mb_strlen
    //max 最大字节, mb_len
    //between 字节数
    //range in_array(一堆字符串)
}
