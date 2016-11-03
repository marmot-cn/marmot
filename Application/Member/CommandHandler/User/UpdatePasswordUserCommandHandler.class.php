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
            return false;
        }
        //检查旧密码是否正确
        $oldEncryptedPassword = $user->getPassword();
        $user->encryptPassword($command->oldPassword, $user->getSalt());
        if ($oldEncryptedPassword != $user->getPassword()) {
            return false;
        }
        
        //设置新的密码和生成新的盐
        $user->encryptPassword($command->password);
        if ($user->updatePassword()) {
            //发布领域事件
            return true;
        }
        return false;
    }
}
