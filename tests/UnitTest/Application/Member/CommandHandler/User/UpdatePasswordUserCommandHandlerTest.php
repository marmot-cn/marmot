<?php
namespace Member\CommandHandler\User;

use PHPUnit\Framework\TestCase;
use Marmot\Framework\Interfaces\ICommand;
use Marmot\Core;

use Member\Model\User;
use Member\Repository\User\UserRepository;
use Member\Utils\ObjectGenerate;
use Member\Command\User\UpdatePasswordUserCommand;

use Prophecy\Argument;

/**
 * Member/CommandHandler/User/UpdatePasswordUserCommandHandler.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160828
 */

class UpdatePasswordUserCommandHandlerTest extends TestCase
{
    private $commandHandler;
    private $childCommandHandler;

    public function setUp()
    {
        $this->commandHandler = new UpdatePasswordUserCommandHandler();
        $this->childCommandHandler = new class extends UpdatePasswordUserCommandHandler{
            public function getUserRepository() : UserRepository
            {
                return parent::getUserRepository();
            }
        };
    }

    public function testCorrectImplementsICommandHandler()
    {
        $this->assertInstanceOf(
            'Marmot\Framework\Interfaces\ICommandHandler',
            $this->commandHandler
        );
    }

    public function testGetUserRepository()
    {
        $this->assertInstanceof(
            'Member\Repository\User\UserRepository',
            $this->childCommandHandler->getUserRepository()
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentException()
    {
        $command = new class implements ICommand {
        };
        $this->commandHandler->execute($command);
    }

    public function testExecuteFailure()
    {
        $faker = \Faker\Factory::create('zh_CN');
        $faker->seed(0);

        $newPassword = $faker->password;
        $oldPassword = 'oldPassword';
        $uid = $faker->randomNumber(3);

        $command = new UpdatePasswordUserCommand(
            $oldPassword,
            $newPassword,
            $uid
        );

        $user = $this->prophesize(User::class);
        $user->changePassword(
            Argument::exact($oldPassword),
            Argument::exact($newPassword)
        )->shouldBeCalledTimes(1)
        ->willReturn(false);

        $repository = $this->prophesize(UserRepository::class);
        $repository->getOne(Argument::exact($uid))
                   ->shouldBeCalledTimes(1)
                   ->willReturn($user);

        $this->commandHandler = $this->getMockBuilder(UpdatePasswordUserCommandHandler::class)
                                     ->setMethods(['getUserRepository'])
                                     ->getMock();
        $this->commandHandler->expects($this->once())
                             ->method('getUserRepository')
                             ->willReturn($repository->reveal());

        $result = $this->commandHandler->execute($command);
        $this->assertFalse($result);
    }

//    public function testExecuteSuccess()
//    {
//    }
}
