<?php
namespace Member\CommandHandler\User;

use System\Interfaces\ICommandHandler;
use System\Interfaces\ICommand;
use System\Classes\Transaction;

use Member\Command\User\SignUpUserCommand;
use Member\Model\User;

class SignUpUserCommandHandler implements ICommandHandler
{
    public function execute(ICommand $command)
    {
        if (!($command instanceof SignUpUserCommand)) {
            throw new \InvalidArgumentException;
        }

        $user = new User();
        $user->setCellPhone($command->cellPhone);
        $user->setUserName($command->cellPhone);
        $user->encryptPassword($command->password);

        //这里只操作一张表,但是这里演示事物,使用事物处理
        Transaction::beginTransaction();
        if ($user->signUp() && Transaction::commit()) {
            $command->uid = $user->getId();
           //发布领域事件
            return true;
        }
        Transaction::rollBack();
        return false;
    }
}
