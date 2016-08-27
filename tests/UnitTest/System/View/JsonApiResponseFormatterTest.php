<?php
namespace System\Classes;

use tests;
use Marmot\Core;
use System\View\JsonApiResponseFormatter;

class JsonApiResponseFormatterTest extends tests\GenericTestCase
{

    private $stub;

    public function setUp()
    {
        $this->stub = new JsonApiResponseFormatter();
    }

    public function tearDown()
    {
    }

    public function testCorrectImplementResponseFormatter()
    {
        $this->assertInstanceof('System\Interfaces\ResponseFormatterInterface', $this->stub);
    }

    public function testFormat()
    {
        $response = new \System\Classes\Response();
        $response->data = array('key'=>'value');

        $this->stub->format($response);

        $this->assertEquals(json_encode(array('key'=>'value')), $response->content);
        $this->assertArraySubset(array('application/vnd.api+json'), $response->getHeaders()['Content-Type']);
    }
}
