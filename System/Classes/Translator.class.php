<?php
namespace System\Classes;

abstract class Translator
{

    abstract protected function getMap() : array;

    abstract protected function getKeys() : array;

    public function arrayToObject(array $expression, $object)
    {
        $keys = $this->getKeys();
        $map = $this->getMap();

        foreach ($keys as $key) {
            $setter='set'.$key;
            if (method_exists($object, $setter)) {
                $object->$setter($expression[$map[$key]]);
            }
        }
        return $object;
    }

    public function objectToArray($object, array $searchKeys = array())
    {
        $expression = array();

        $keys = !empty($searchKeys) ? $searchKeys : $this->getKeys();
        $map = $this->getMap();

        foreach ($keys as $key) {
            $getter='get'.$key;
            if (method_exists($object, $getter)) {
                $expression[$map[$key]] = $object->$getter();
            }
        }

        return $expression;
    }
}
