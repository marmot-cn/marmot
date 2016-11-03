<?php
namespace System\View;

use tests\GenericTestCase;
use Marmot\Core;

class EmptyViewTest extends GenericTestCase
{

    private $stub;

    public function setUp()
    {
        $this->stub = new EmptyView();
    }

    public function tearDown()
    {
    }

    public function testCorrectImplementIView()
    {
        $this->assertInstanceof('System\Interfaces\IView', $this->stub);
    }

    public function testConstructor()
    {
        $rulesParameter = $this->getPrivateProperty('System\View\EmptyView', 'rules');
        $this->assertEmpty($rulesParameter->getValue($this->stub));

        $dataParameter = $this->getPrivateProperty('System\View\EmptyView', 'data');
        $this->assertEmpty($dataParameter->getValue($this->stub));
    }

    public function testDisplay()
    {
        $this->assertEmpty($this->stub->display());
    }
}
