<?php
namespace System\Strategy\Validate;

use System\Classes\ValidateStrategy;
use System\Interfaces\IValidateStrategy;

class EmailStrategy implements IValidateStrategy
{
    use ValidateStrategy;

    public function typeRule() : bool
    {
    }
}
