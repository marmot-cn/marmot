<?php
namespace Member\CommandHandler\User;

use System\Interfaces\ICommandHandler;
use System\Interfaces\ICommand;
use System\Interfaces\INull;

use Member\Model\User;
use Member\Command\User\UpdatePasswordUserCommand;
use Member\Repository\User\UserRepository;
use Marmot\Core;

class UpdatePasswordUserCommandHandler implements ICommandHandler
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = Core::$container->get('Member\Repository\User\UserRepository');
    }

    public function __destruct()
    {
        unset($this->userRepository);
    }

    protected function getUserRepository() : UserRepository
    {
        return $this->userRepository;
    }

    public function execute(ICommand $command)
    {
        if (!($command instanceof UpdatePasswordUserCommand)) {
            throw new \InvalidArgumentException;
        }

        $repository = $this->getUserRepository();
        $user = $repository->getOne($command->uid);

        return $user->changePassword($command->oldPassword, $command->password);
    }
}
