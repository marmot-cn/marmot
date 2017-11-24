<?php
namespace Member\CommandHandler\User;

use System\Interfaces\ICommandHandler;
use System\Interfaces\ICommand;

use Member\Command\User\SignUpUserCommand;
use Member\Model\User;

class SignUpUserCommandHandler implements ICommandHandler
{

    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function __destruct()
    {
        unset($this->user);
    }

    protected function getUser()
    {
        return $this->user;
    }

    public function execute(ICommand $command)
    {
        if (!($command instanceof SignUpUserCommand)) {
            throw new \InvalidArgumentException;
        }

        $user = $this->getUser();
        $user->setCellphone($command->cellphone);
        $user->setUserName($command->cellphone);
        $user->encryptPassword($command->password);

        //这里只操作一张表,但是这里演示事物,使用事物处理
        if ($user->signUp()) {
            $command->uid = $user->getId();
            //发布领域事件
            return true;
        }
        return false;
    }
}
