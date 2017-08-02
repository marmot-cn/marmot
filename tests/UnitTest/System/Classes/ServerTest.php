<?php
namespace System\Classes;

use tests\GenericTestCase;
use Marmot\Core;

class ServerTest extends GenericTestCase
{
    private $stub;

    public function setUp()
    {
        $this->stub = new Server();
    }

    public function testHostInCli()
    {
        $this->assertEmpty($this->stub->host());
    }
}
