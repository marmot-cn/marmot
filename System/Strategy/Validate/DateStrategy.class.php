<?php
namespace System\Strategy\Validate;

use System\Classes\ValidateStrategy;
use System\Interfaces\IValidateStrategy;

class DateStrategy implements IValidateStrategy
{
    use ValidateStrategy;

    public function typeRule() : bool
    {
        return strtotime($this->getVerifyValue()) != false;
    }
}
