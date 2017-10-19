<?php
namespace System\Classes;

use Marmot\Core;

trait ValidateStrategy
{
    private $verifyValue;

    public function validate($verifyValue, string $options = '', int $errorCode = 0) : bool
    {
        $this->verifyValue = $verifyValue;

        if (!$this->typeRule()) {
            return false;
        }

        if (empty($options)) {
            return true;
        }
        $options = explode('|', $options);

        foreach ($options as $option) {
            list($method, $parameters) = explode(':', $option);
            $method .= 'Rule';

            if (!method_exists($this, $method)) {
                //errorCode method not exist
                return false;
            }

            $parameters = explode(',', $parameters);
            $result = call_user_func_array(array($this, $method), $parameters);

            if (!$result) {
                Core::setLastError($errorCode);
                return false;
            }
        }

        return true;
    }

    protected function getVerifyValue()
    {
        return $this->verifyValue;
    }

    abstract protected function typeRule() : bool;
}
