<?php
namespace Member\CommandHandler\User;

use System\Interfaces\ICommandHandler;
use System\Interfaces\ICommand;
use Member\Model\User;
use Marmot\Core;
use Member\Command\User\UpdatePasswordUserCommand;

class UpdatePasswordUserCommandHandler implements ICommandHandler
{
    public function execute(ICommand $command)
    {
        if (!($command instanceof UpdatePasswordUserCommand)) {
            throw new \InvalidArgumentException;
        }

        $repository = Core::$container->get('Member\Repository\User\UserRepository');
        $user = $repository->getOne($command->uid);
        //确认用户是否存在
        if (!$user instanceof User) {
            Core::setLastError(RESOURCE_NOT_EXIST);
            return false;
        }

        if ($user->verifyPassword($command->oldPassword)
            &&$user->updatePassword($command->password)) {
            //发布领域事件
            return true;
        }
        return false;
    }
}
