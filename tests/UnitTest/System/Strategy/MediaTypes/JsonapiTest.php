<?php
namespace System\Strategy\MediaTypes;

use PHPUnit\Framework\TestCase;
use System\Classes\Request;
use Marmot\Core;

class JsonapiTest extends TestCase
{

    public function setUp()
    {
        $this->strategy = new JsonapiStrategy();
        $this->request = $this->prophesize(Request::class);
    }

    public function tearDown()
    {
        unset($this->strategy);
        unset($this->request);
    }

    public function testImplementsIMediaTypeStrategy()
    {
        $this->assertInstanceOf('System\Interfaces\IMediaTypeStrategy', $this->strategy);
    }

    public function testInCorrectContentTypeHeaderWithPostMethod()
    {
        $this->request->isPostMethod()->willReturn(true)->shouldBeCalledTimes(1);
        $this->request->getHeader('content-type', '')->willReturn('text/html')->shouldBeCalled();
        $this->request->getHeader('accept', '')->willReturn('application/vnd.api+json');

        $result = $this->strategy->validate($this->request->reveal());
        $this->assertFalse($result);

        $this->assertEquals(UNSUPPORTED_MEDIA_TYPE, Core::getLastError()->getCode());
    }

    public function testInCorrectContentTypeHeaderWithPutMethod()
    {
        $this->request->isPostMethod()->willReturn(false)->shouldBeCalledTimes(1);
        $this->request->isPutMethod()->willReturn(true)->shouldBeCalledTimes(1);
        $this->request->getHeader('content-type', '')->willReturn('text/html')->shouldBeCalled();
        $this->request->getHeader('accept', '')->willReturn('application/vnd.api+json');

        $result = $this->strategy->validate($this->request->reveal());
        $this->assertFalse($result);

        $this->assertEquals(UNSUPPORTED_MEDIA_TYPE, Core::getLastError()->getCode());
    }

    public function testCorrectContentTypeHeaderButWithParameters()
    {
        $this->request->isPostMethod()->willReturn(true)->shouldBeCalledTimes(1);
        $this->request->getHeader('content-type', '')->willReturn('application/vnd.api+json;q=0.8')->shouldBeCalled();
        $this->request->getHeader('accept', '')->willReturn('application/vnd.api+json');

        $result = $this->strategy->validate($this->request->reveal());
        $this->assertFalse($result);
       
        $this->assertEquals(UNSUPPORTED_MEDIA_TYPE, Core::getLastError()->getCode());
    }

    public function testAllContentTypeHeader()
    {
        $this->request->isPostMethod()->willReturn(true)->shouldBeCalledTimes(1);
        $this->request->getHeader('content-type', '')->willReturn('text/html;q=0.2,*/*;q=0.8')->shouldBeCalled();
        $this->request->getHeader('accept', '')->willReturn('application/vnd.api+json')->shouldBeCalled();
        $this->request->getRawBody()->willReturn(json_encode(array('test')))->shouldBeCalledTimes(1);

        $result = $this->strategy->validate($this->request->reveal());
        $this->assertTrue($result);
    }

    public function testInCorrectAcceptHeader()
    {
        $this->request->isPostMethod()->willReturn(true);
        $this->request->getHeader('content-type', '')->willReturn('application/vnd.api+json');
        $this->request->getHeader('accept', '')->willReturn('text/html')->shouldBeCalled();

        $result = $this->strategy->validate($this->request->reveal());
        $this->assertFalse($result);
       
        $this->assertEquals(NOT_ACCEPTABLE_MEDIA_TYPE, Core::getLastError()->getCode());
    }

    public function testCorrectAcceptHeaderButWithParameters()
    {
        $this->request->isPostMethod()->willReturn(true);
        $this->request->getHeader('content-type', '')->willReturn('application/vnd.api+json');
        $this->request->getHeader('accept', '')->willReturn('application/vnd.api+json;q=0.8')->shouldBeCalled();

        $result = $this->strategy->validate($this->request->reveal());
        $this->assertFalse($result);
       
        $this->assertEquals(NOT_ACCEPTABLE_MEDIA_TYPE, Core::getLastError()->getCode());
    }

    public function testAllAccpetHeader()
    {
        $this->request->isPostMethod()->willReturn(true)->shouldBeCalledTimes(1);
        $this->request->getHeader('content-type', '')->willReturn('application/vnd.api+json')->shouldBeCalled();
        $this->request->getHeader('accept', '')->willReturn('text/html;q=0.2,*/*;q=0.8')->shouldBeCalled();
        $this->request->getRawBody()->willReturn(json_encode(array('test')))->shouldBeCalledTimes(1);

        $result = $this->strategy->validate($this->request->reveal());
        $this->assertTrue($result);
    }

    public function testIncorrectBody()
    {
        $this->request->isPostMethod()->willReturn(true)->shouldBeCalledTimes(1);
        $this->request->getHeader('content-type', '')->willReturn('application/vnd.api+json')->shouldBeCalled();
        $this->request->getHeader('accept', '')->willReturn('application/vnd.api+json')->shouldBeCalled();
        $this->request->getRawBody()->willReturn(array('test'))->shouldBeCalledTimes(1);

        $result = $this->strategy->validate($this->request->reveal());
        $this->assertFalse($result);
       
        $this->assertEquals(INCORRECT_RAW_BODY, Core::getLastError()->getCode());
    }

    public function testCorrectHeaderAndBody()
    {
        $this->request->isPostMethod()->willReturn(true)->shouldBeCalledTimes(1);
        $this->request->getHeader('content-type', '')->willReturn('application/vnd.api+json')->shouldBeCalled();
        $this->request->getHeader('accept', '')->willReturn('application/vnd.api+json')->shouldBeCalled();
        $this->request->getRawBody()->willReturn(json_encode(array('test')))->shouldBeCalledTimes(1);

        $result = $this->strategy->validate($this->request->reveal());
        $this->assertTrue($result);
    }

    public function testDecode()
    {
        $this->assertEquals(array('test'), $this->strategy->decode(json_encode(array('test'))));
    }
}
