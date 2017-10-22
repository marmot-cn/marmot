<?php
//powered by kevin
namespace System\Classes;

use System\Interfaces\ICommandHandlerFactory;
use System\Interfaces\ICommand;
use System\Classes\Transaction;

/**
 * 命令总线
 * 1. 构造总线传递 commandHandlerFactory
 * 2. 发送命令,通过 commandHandlerFactory 获取到适当的 commandHandler
 * 3. 执行 commandHandler
 *
 */
class CommandBus
{
    
    //transaction
    
    private $commandHandlerFactory;

    public function __construct(ICommandHandlerFactory $commandHandlerFactory)
    {
        $this->commandHandlerFactory = $commandHandlerFactory;
    }

    private function getCommandHandlerFactory() : ICommandHandlerFactory
    {
        return $this->commandHandlerFactory;
    }

    public function send(ICommand $command)
    {
        $handler = $this->getCommandHandlerFactory()->getHandler($command);
        
        //transaction start
        if ($handler != null) {
            return $handler->execute($command);
        }
        //truansaction end
        
        //log
        
        //error
        return false;
    }
}
