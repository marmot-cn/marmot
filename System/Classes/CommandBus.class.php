<?php
//powered by kevin
namespace System\Classes;

use System\Interfaces\ICommandHandlerFactory;
use System\Interfaces\ICommand;

/**
 * 命令总线
 * 1. 构造总线传递 commandHandlerFactory
 * 2. 发送命令,通过 commandHandlerFactory 获取到适当的 commandHandler
 * 3. 执行 commandHandler
 *
 * @codeCoverageIgnore
 */
class CommandBus
{
    
    private $commandHandlerFactory;

    public function __construct(ICommandHandlerFactory $commandHandlerFactory)
    {
        $this->commandHandlerFactory = $commandHandlerFactory;
    }

    public function send(ICommand $command)
    {
        $handler = $this->commandHandlerFactory->getHandler($command);

        if ($handler != null) {
            return $handler->execute($command);
        } else {
            //@todo
            //exception
        }
    }
}
