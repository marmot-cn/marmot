<?php
namespace System\Classes;

use tests;
use Marmot\Core;
use System\Classes\Request;

/**
 * 用于测试Request类接收不同方式的传参正确性
 * 1. 判断HTTP METHOD正确性
 * 2. 接收传参正确性
 */
class RequestTest extends tests\GenericTestCase
{

    private $stub;

    public function setUp()
    {
        $this->stub = new Request();
    }

    public function tearDown()
    {
        unset($this->stub);
        unset($_SERVER['REQUEST_METHOD']);
        unset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        unset($_POST);
        unset($_GET);
    }

    /**
     * 测试默认Method方法
     */
    public function testGetMethodDefault()
    {
        $method = $this->stub->getMethod();
        $this->assertEquals('GET', $method);
    }

    /**
     * 测试 REQUEST_METHOD
     */
    public function testGetMethodRequestMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $method = $this->stub->getMethod();
        $this->assertEquals('POST', $method);
    }

    /**
     * 测试 HTTP_X_HTTP_METHOD_OVERRIDE
     */
    public function testGetMethodXHttpMethodOverride()
    {
        $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] = 'POST';
        $method = $this->stub->getMethod();
        $this->assertEquals('POST', $method);
    }

    /**
     * 测试 HTTP_X_HTTP_METHOD_OVERRIDE 覆盖
     * REQUEST_METHOD
     */
    public function testGetMethodXHttpMethodOverrideRequestMethod()
    {
        $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] = 'PUT';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $method = $this->stub->getMethod();
        $this->assertEquals('PUT', $method);
    }

    /**
     * 测试正确GET方法,期望返回true
     */
    public function testGetIsGetWithGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertTrue($this->stub->getIsGet());
    }

    /**
     * 测试非GET方法,期望返回false
     */
    public function testGetIsGetWithNotGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertFalse($this->stub->getIsGet());
    }

    /**
     * 测试正确OPTIONS方法,期望返回true
     */
    public function testGetIsOptionsWithGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';
        $this->assertTrue($this->stub->getIsOptions());
    }

    /**
     * 测试非OPTIONS方法,期望返回false
     */
    public function testGetIsOptionsWithNotGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertFalse($this->stub->getIsOptions());
    }

    /**
     * 测试正确POST方法,期望返回true
     */
    public function testGetIsHeadWithGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'HEAD';
        $this->assertTrue($this->stub->getIsHead());
    }

    /**
     * 测试非HEAD方法,期望返回false
     */
    public function testGetIsHeadWithNotGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertFalse($this->stub->getIsHead());
    }

    /**
     * 测试正确POST方法,期望返回true
     */
    public function testGetIsPostWithGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertTrue($this->stub->getIsPost());
    }

    /**
     * 测试非POST方法,期望返回false
     */
    public function testGetIsPostWithNotGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertFalse($this->stub->getIsPost());
    }

    /**
     * 测试正确DELETE方法,期望返回true
     */
    public function testGetIsDeleteWithGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $this->assertTrue($this->stub->getIsDelete());
    }

    /**
     * 测试非DELETE方法,期望返回false
     */
    public function testGetIsDeleteWithNotGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertFalse($this->stub->getIsDelete());
    }

    /**
     * 测试正确PUT方法,期望返回true
     */
    public function testGetIsPutWithGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->assertTrue($this->stub->getIsPut());
    }

    /**
     * 测试非PUT方法,期望返回false
     */
    public function testGetIsPutWithNotGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertFalse($this->stub->getIsPut());
    }

    /**
     * 测试正确PATCH方法,期望返回true
     */
    public function testGetIsPatchWithGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'PATCH';
        $this->assertTrue($this->stub->getIsPatch());
    }

    /**
     * 测试非PATCH方法,期望返回false
     */
    public function testGetIsPatchWithNotGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertFalse($this->stub->getIsPatch());
    }

    /**
     * 测试通过setRawBody获取getRawBody,
     * 暂时没法测试 php://input
     */
    public function testGetRawBody()
    {
        $this->stub->setRawBody('test');

        $rawBody = $this->stub->getRawBody();

        $this->assertEquals('test', $rawBody);
    }

    /**
     * 测试通过setQueryParams获取getQueryParams
     */
    public function testGetQueryParamsWithSetQueryParams()
    {
        $this->stub->setQueryParams(array('key'=>'value'));
        $queryParams = $this->stub->getQueryParams();
        $this->assertEquals(array('key'=>'value'), $queryParams);
    }

    /**
     * 测试不通过setQueryParams获取getQueryParams
     */
    public function testGetQueryParamsWithoutSetQueryParams()
    {
        $_GET['key'] = 'value';
        $queryParams = $this->stub->getQueryParams();
        $this->assertEquals(array('key'=>'value'), $queryParams);
    }

    /**
     * 测试getQueryParam方法,key存在的情况下,不设置默认值.
     */
    public function testGetQueryParamWithExistKey()
    {
        $_GET['key'] = 'value';
        $queryParam = $this->stub->getQueryParam('key');
        $this->assertEquals('value', $queryParam);
    }

    /**
     * 测试getQueryParam方法,key存在的情况下,设置默认值.
     */
    public function testGetQueryParamWithExistKeyAndDefaultValue()
    {
        $_GET['key'] = 'value';
        $queryParam = $this->stub->getQueryParam('key', 'value2');
        $this->assertEquals('value', $queryParam);
    }

    /**
     * 测试getQueryParam方法,key不存在的情况下,不设置默认值.
     */
    public function testGetQueryParamWithoutExistKey()
    {
        $_GET['key'] = 'value';
        $queryParam = $this->stub->getQueryParam('key1');
        $this->assertNull($queryParam);
    }

    /**
     * 测试getQueryParam方法,key不存在的情况下,设置默认值.
     */
    public function testGetQueryParamWithoutExistKeyAndDefaultValue()
    {
        $_GET['key'] = 'value';
        $queryParam = $this->stub->getQueryParam('key1', 'value1');
        $this->assertEquals('value1', $queryParam);
    }

    /**
     * 测试 setBodyParams 和 getBodyParams
     */
    public function testSetBodyParams()
    {
        $this->stub->setBodyParams(array('key'=>'value'));
        $this->assertEquals(array('key'=>'value'), $this->stub->getBodyParams());
    }

    /**
     * 测试 getBodyParams 通过 post 方法 和 $_POST 传参
     */
    public function testGetBodyParamsWithPostMethodAndPOST()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        //通过$_POST传值
        $_POST['key'] = 'value';
        $this->assertEquals($_POST, $this->stub->getBodyParams());
    }

    /**
     * 测试 getBodyParams 通过 post 方法和 setBodyParams 传值
     */
    public function testGetBodyParamsWithPostMethodAndSetBodyParams()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        //通过setBodyParams传值
        $this->stub->setRawBody(json_encode(array('key1'=>'value1')));
        $this->assertEquals(array('key1'=>'value1'), $this->stub->getBodyParams());
    }

    /**
     * 测试 getBodyParams 通过 post 方法 和 $_POST 传参
     */
    public function testGetBodyParamsWithPutMethodAndPOST()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        //通过$_POST传值
        $_POST['key'] = 'value';
        $this->assertEquals($_POST, $this->stub->getBodyParams());
    }

    /**
     * 测试 getBodyParams 通过 post 方法和 setBodyParams 传值
     */
    public function testGetBodyParamsWithPutMethodAndSetBodyParams()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        //通过setBodyParams传值
        $this->stub->setRawBody(json_encode(array('key1'=>'value1')));
        $this->assertEquals(array('key1'=>'value1'), $this->stub->getBodyParams());
    }

    /**
     * 测试 getBodyParams 通过 非post 方法 和 非put 方法传参
     */
    public function testGetBodyParamsWithoutPostAndPutMethod()
    {
        //通过$_POST传值
        $this->stub->setRawBody('key=value');
        $this->assertEquals(array('key'=>'value'), $this->stub->getBodyParams());
    }

    /**
     * 测试getQueryParam方法,key存在的情况下,不设置默认值.
     */
    public function testGetBodyParamWithExistKey()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['key'] = 'value';
        $bodyParam = $this->stub->getBodyParam('key');
        $this->assertEquals('value', $bodyParam);
    }

    /**
     * 测试getBodyParam方法,key存在的情况下,设置默认值.
     */
    public function testGetBodyParamWithExistKeyAndDefaultValue()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['key'] = 'value';
        $bodyParam = $this->stub->getBodyParam('key', 'value2');
        $this->assertEquals('value', $bodyParam);
    }

    /**
     * 测试getBodyParam方法,key不存在的情况下,不设置默认值.
     */
    public function testGetBodyParamWithoutExistKey()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['key'] = 'value';
        $bodyParam = $this->stub->getBodyParam('key1');
        $this->assertNull($bodyParam);
    }

    /**
     * 测试getBodyParam方法,key不存在的情况下,设置默认值.
     */
    public function testGetBodyParamWithoutExistKeyAndDefaultValue()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['key'] = 'value';
        $bodyParam = $this->stub->getBodyParam('key1', 'value1');
        $this->assertEquals('value1', $bodyParam);
    }
}
