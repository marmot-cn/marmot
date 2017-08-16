<?php
namespace System\View;

use tests;

use Marmot\Core;
use System\Classes\Response;

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
        $this->assertInstanceof('System\Interfaces\IResponseFormatter', $this->stub);
    }

    public function testFormat()
    {
        $response = new Response();
        $response->data = array('key'=>'value');

        $this->stub->format($response);

        $this->assertEquals(array('key'=>'value'), $response->content);
        $this->assertArraySubset(array('application/vnd.api+json'), $response->getHeaders()['Content-Type']);
    }
}
