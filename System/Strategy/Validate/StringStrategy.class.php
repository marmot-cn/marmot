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
}
