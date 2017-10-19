<?php
namespace System\Interfaces;

/**
 * 翻译器接口
 * @codeCoverageIgnore
 */
interface ITranslator
{
    
    public function arrayToObject(array $expression);

    public function objectToArray($object, array $keys = array());
}
