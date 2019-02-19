<?php
namespace Member\CommandHandler\User;

use Marmot\Framework\Interfaces\ICommandHandlerFactory;
use Marmot\Framework\Interfaces\ICommandHandler;
use Marmot\Framework\Interfaces\ICommand;

use Marmot\Framework\Classes\NullCommandHandler;
use Marmot\Core;

class UserCommandHandlerFactory implements ICommandHandlerFactory
{
    
    public function getHandler(ICommand $command) : ICommandHandler
    {
        $commandHandler = '';

        switch (get_class($command)) {
            //注册
            case 'Member\Command\User\SignUpUserCommand':
                $commandHandler = new SignUpUserCommandHandler();
                break;
            //修改密码
            case 'Member\Command\User\UpdatePasswordUserCommand':
                $commandHandler = new UpdatePasswordUserCommandHandler();
                break;
            //默认
            default:
                $commandHandler = new NullCommandHandler();
                break;
        }
        return $commandHandler;
    }
}
