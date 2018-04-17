<?php
namespace System\Classes;

use PHPUnit\Framework\TestCase;

use Marmot\Core;
use System\Classes\NullCommandHandler;
use System\Interfaces\ICommand;

class NullCommandHandlerTest extends TestCase
{
    private $nullCommandHandler;

    public function setUp()
    {
        $this->nullCommandHandler = new NullCommandHandler();
    }

    public function testImplementsNull()
    {
        $this->assertInstanceOf('System\Interfaces\INull', $this->nullCommandHandler);
    }

    public function testExecute()
    {
        $command = $this->getMockBuilder(ICommand::class)
                        ->getMock();
        
        $result = $this->nullCommandHandler->execute($command);
        $this->assertFalse($result);
        $this->assertEquals(COMMAND_HANDLER_NOT_EXIST, Core::getLastError()->getId());
    }
}
