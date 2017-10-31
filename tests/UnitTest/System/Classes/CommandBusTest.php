<?php
namespace System\Classes;

use tests\GenericTestCase;

use Marmot\Core;
use System\Classes\CommandBus;
use System\Interfaces\ICommandHandlerFactory;
use System\Interfaces\ICommand;
use System\Interfaces\INull;
use System\Classes\NullCommandHandler;

use Prophecy\Argument;

class CommandBusTest extends GenericTestCase
{
    private $commandBus;
    private $commandHandler;
    private $command;
    private $transaction;

    public function setUp()
    {
        $this->commandHandlerFactory = $this->prophesize(ICommandHandlerFactory::class);
        $this->command = $this->prophesize(ICommand::class);
        $this->transaction = $this->prophesize(Transaction::class);
    }

    public function tearDown()
    {
        unset($this->commandBus);
        unset($this->commandHandlerFactory);
        unset($this->command);
        unset($this->transaction);
    }
    
    public function testNullCommandHandler()
    {
        $commandHandler = $this->getMockBuilder(NullCommandHandler::class)
                               ->getMock();

        $this->commandHandlerFactory->getHandler(
            Argument::exact($this->command)
        )->shouldBeCalledTimes(1)
         ->willReturn($commandHandler);

        $this->commandBus= $this->getMockBuilder(CommandBus::class)
                         ->setMethods(['getTransaction', 'getCommandHandlerFactory'])
                         ->setConstructorArgs([$this->commandHandlerFactory->reveal()])
                         ->getMock();

        $this->commandBus->expects($this->once())
                         ->method('getCommandHandlerFactory')
                         ->willReturn($this->commandHandlerFactory->reveal());
        
        $this->commandBus->expects($this->exactly(0))
                         ->method('getTransaction');

        $result = $this->commandBus->send($this->command->reveal());
        $this->assertFalse($result);
        $this->assertEquals(COMMAND_HANDLER_NOT_EXIST, Core::getLastError()->getId());
    }

    public function testCommandExcuteFailure()
    {
    }

    public function testCommitFailure()
    {
    }

    public function testSendSuccess()
    {
    }
}
