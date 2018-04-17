<?php
namespace System\Classes;

use Marmot\Core;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    private $error;

    public function setUp()
    {
        $this->error = new Error();
    }

    public function tearDown()
    {
        unset($this->error);
    }

    public function testDefaultConstructor()
    {
        $this->assertEquals(0, $this->error->getId());
        $this->assertEquals('', $this->error->getLink());
        $this->assertEquals('', $this->error->getStatus());
        $this->assertEquals('', $this->error->getCode());
        $this->assertEquals('', $this->error->getTitle());
        $this->assertEquals('', $this->error->getDetail());
        $this->assertEquals(array(), $this->error->getSource());
        $this->assertEquals(array(), $this->error->getMeta());
    }

    public function testConstructor()
    {
        $this->error = new Error(
            10001,
            'link',
            403,
            10001,
            'title',
            'detail',
            array(
                'pointer'=>'/data/attributes/cellPhone'
            ),
            array(
                'meta'
            )
        );

        $this->assertEquals(10001, $this->error->getId());
        $this->assertEquals('link', $this->error->getLink());
        $this->assertEquals('403', $this->error->getStatus());
        $this->assertEquals('10001', $this->error->getCode());
        $this->assertEquals('title', $this->error->getTitle());
        $this->assertEquals('detail', $this->error->getDetail());
        $this->assertEquals(
            array('pointer'=>'/data/attributes/cellPhone'),
            $this->error->getSource()
        );
        $this->assertEquals(array('meta'), $this->error->getMeta());
    }
}
