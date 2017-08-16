<?php
namespace System\Strategy\Validate;

use System\Classes\ValidateStrategy;
use System\Interfaces\IValidateStrategy;

class IntStrategy implements IValidateStrategy
{
    use ValidateStrategy;

    /**
     * 检测是否为整型
     */
    public function typeRule() : bool
    {
        return is_int($this->getVerifyValue());
    }

    /**
     * 检测是大于最小数 min:xxx
     */
    private function minRule(int $minNumber) : bool
    {
        return $this->getVerifyValue() >= $minNumber;
    }

    /**
     * 检测是小于最大数 max:xxx
     */
    private function maxRule(int $maxNumber) : bool
    {
        return $this->getVerifyValue() <= $maxNumber;
    }

    /**
     * 检测数字是否属于一个区间 between:min,max
     */
    private function betweenRule(int $min, int $max) : bool
    {
        return $this->minRule($min) && $this->maxRule($max);
    }

    /**
     * 检测数字是否属于一个范围 range:1,2,3,4...
     */
    private function rangeRule(int ...$numbers) : bool
    {
        return in_array($this->getVerifyValue(), $numbers);
    }
}
