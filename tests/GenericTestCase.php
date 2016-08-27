<?php
namespace tests;

/**
 * 框架自用通用测试类,封装了一些常用的函数用于测试private参数等方法
 * @author chloroplast
 * @version 1.0.20160218
 */

abstract class GenericTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * getPrivateMethod
     *
     * @author  Joe Sexton <joe@webtipblog.com>
     * @param   string $className
     * @param   string $methodName
     * @return  ReflectionMethod
     */
    public function getPrivateMethod($className, $methodName)
    {
        $reflector = new \ReflectionClass($className);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);
 
        return $method;
    }

    /**
     * getPrivateProperty
     *
     * @author  Joe Sexton <joe@webtipblog.com>
     * @param   string $className
     * @param   string $propertyName
     * @return  ReflectionProperty
     */
    public function getPrivateProperty($className, $propertyName)
    {
        $reflector = new \ReflectionClass($className);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);
 
        return $property;
    }
}
