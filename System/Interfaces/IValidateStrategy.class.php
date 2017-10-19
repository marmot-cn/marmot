<?php
namespace System\Interfaces;

interface IValidateStrategy
{
    public function validate($verifyValue, string $options = '', int $errorCode = 0) : bool;

    public function typeRule() : bool;
}
