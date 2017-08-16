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

    public function minRule($minDate) : bool
    {
        return $this->timeStamp($this->getVerifyValue()) >= $this->timeStamp($minDate);
    }

    public function maxRule($minDate) : bool
    {
        return $this->timeStamp($this->getVerifyValue()) <= $this->timeStamp($minDate);
    }

    private function timeStamp($time)
    {
        return strtotime($time) ? strtotime($time) :false;
    }
}
