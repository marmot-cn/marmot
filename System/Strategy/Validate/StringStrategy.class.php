<?php
namespace System\Strategy\Validate;

use System\Classes\ValidateStrategy;
use System\Interfaces\IValidateStrategy;

class StringStrategy implements IValidateStrategy
{
    use ValidateStrategy;

    /**
     * 检测是否为字符串
     */
    public function typeRule() : bool
    {
        return is_string($this->getVerifyValue());
    }

    public function minRule(int $minStrLen) : bool
    {
        return $this->length($this->getVerifyValue()) >= $minStrLen;
    }

    public function maxRule(int $maxStrLen) : bool
    {
        return $this->length($this->getVerifyValue()) <= $maxStrLen;
    }

    public function betweenRule(int $minStrLen, int $maxStrLen) : bool
    {
        return $this->minRule($minStrLen) && $this->maxRule($maxStrLen);
    }

    public function rangeRule(string ...$string) : bool
    {
        return in_array($this->getVerifyValue(), $string);
    }

    public function regularRule($regular) : bool
    {
        return preg_match($regular, $this->getVerifyValue());
    }

    private function length($string)
    {
        return mb_strlen($string, 'UTF-8');
    }
}
