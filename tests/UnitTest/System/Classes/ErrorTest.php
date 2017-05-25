<?php
namespace System\Classes;

use Marmot\Core;
use tests\GenericTestCase;

class ErrorTest extends GenericTestCase
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
        $idParameter = $this->getPrivateProperty('System\Classes\Error', 'id');
        $this->assertEquals(0, $idParameter->getValue($this->error));
        $this->assertEquals(0, $this->error->getId());

        $linkParameter = $this->getPrivateProperty('System\Classes\Error', 'link');
        $this->assertEquals('', $linkParameter->getValue($this->error));
        $this->assertEquals('', $this->error->getLink());

        $statusParameter = $this->getPrivateProperty('System\Classes\Error', 'status');
        $this->assertEquals('', $statusParameter->getValue($this->error));
        $this->assertEquals('', $this->error->getStatus());

        $codeParameter = $this->getPrivateProperty('System\Classes\Error', 'code');
        $this->assertEquals('', $codeParameter->getValue($this->error));
        $this->assertEquals('', $this->error->getCode());

        $titleParameter = $this->getPrivateProperty('System\Classes\Error', 'title');
        $this->assertEquals('', $titleParameter->getValue($this->error));
        $this->assertEquals('', $this->error->getTitle());

        $detailParameter = $this->getPrivateProperty('System\Classes\Error', 'detail');
        $this->assertEquals('', $detailParameter->getValue($this->error));
        $this->assertEquals('', $this->error->getDetail());

        $sourceParameter = $this->getPrivateProperty('System\Classes\Error', 'source');
        $this->assertEquals(array(), $sourceParameter->getValue($this->error));
        $this->assertEquals(array(), $this->error->getSource());

        $metaParameter = $this->getPrivateProperty('System\Classes\Error', 'meta');
        $this->assertEquals(array(), $metaParameter->getValue($this->error));
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

        $idParameter = $this->getPrivateProperty('System\Classes\Error', 'id');
        $this->assertEquals(10001, $idParameter->getValue($this->error));
        $this->assertEquals(10001, $this->error->getId());

        $linkParameter = $this->getPrivateProperty('System\Classes\Error', 'link');
        $this->assertEquals('link', $linkParameter->getValue($this->error));
        $this->assertEquals('link', $this->error->getLink());

        $statusParameter = $this->getPrivateProperty('System\Classes\Error', 'status');
        $this->assertEquals('403', $statusParameter->getValue($this->error));
        $this->assertEquals('403', $this->error->getStatus());

        $codeParameter = $this->getPrivateProperty('System\Classes\Error', 'code');
        $this->assertEquals('10001', $codeParameter->getValue($this->error));
        $this->assertEquals('10001', $this->error->getCode());

        $titleParameter = $this->getPrivateProperty('System\Classes\Error', 'title');
        $this->assertEquals('title', $titleParameter->getValue($this->error));
        $this->assertEquals('title', $this->error->getTitle());

        $detailParameter = $this->getPrivateProperty('System\Classes\Error', 'detail');
        $this->assertEquals('detail', $detailParameter->getValue($this->error));
        $this->assertEquals('detail', $this->error->getDetail());

        $sourceParameter = $this->getPrivateProperty('System\Classes\Error', 'source');
        $this->assertEquals(
            array('pointer'=>'/data/attributes/cellPhone'),
            $sourceParameter->getValue($this->error)
        );
        $this->assertEquals(
            array('pointer'=>'/data/attributes/cellPhone'),
            $this->error->getSource()
        );

        $metaParameter = $this->getPrivateProperty('System\Classes\Error', 'meta');
        $this->assertEquals(array('meta'), $metaParameter->getValue($this->error));
        $this->assertEquals(array('meta'), $this->error->getMeta());
    }
}
