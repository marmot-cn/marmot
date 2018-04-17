<?php
namespace System\Classes;

use PHPUnit\Framework\TestCase;
use Marmot\Core;

class ServerTest extends TestCase
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
