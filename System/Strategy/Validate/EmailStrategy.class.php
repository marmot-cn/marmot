<?php
namespace System\Strategy\Validate;

use System\Classes\ValidateStrategy;
use System\Interfaces\IValidateStrategy;

class EmailStrategy implements IValidateStrategy
{
    use ValidateStrategy;

    public function typeRule() : bool
    {
        return filter_var($this->getVerifyValue(), FILTER_VALIDATE_EMAIL);
    }
}
