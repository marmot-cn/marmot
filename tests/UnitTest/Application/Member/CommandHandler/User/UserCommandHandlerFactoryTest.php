<?php
namespace Member\CommandHandler\User;

use PHPUnit\Framework\TestCase;

use Marmot\Framework\Classes\NullCommandHandler;
use Marmot\Framework\Interfaces\ICommand;

use Member\Command\User\SignUpUserCommand;
use Member\Command\User\UpdatePasswordUserCommand;

class UserCommandHandlerFactoryTest extends TestCase
{

    private $commandHandler;
    private $faker;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create('zh_CN');
        //初始化工厂桩件
        $this->commandHandler = new UserCommandHandlerFactory();
    }

    public function testDefaultCommandHandler()
    {
        $command = $this->getMockBuilder(ICommand::class)
                        ->getMock();
        $commandHandler = $this->commandHandler->getHandler(
            $command
        );

        $this->assertInstanceOf('Marmot\Framework\Classes\NullCommandHandler', $commandHandler);
    }

    public function testSignUpUserCommandHandler()
    {
        $commandHandler = $this->commandHandler->getHandler(
            new SignUpUserCommand(
                $this->faker->phoneNumber,
                $this->faker->password
            )
        );

        $this->assertInstanceOf('Marmot\Framework\Interfaces\ICommandHandler', $commandHandler);
        $this->assertInstanceOf('Member\CommandHandler\User\SignUpUserCommandHandler', $commandHandler);
    }

    public function testUpdatePasswordUserCommandHandler()
    {
        $commandHandler = $this->commandHandler->getHandler(
            new UpdatePasswordUserCommand(
                md5($this->faker->password.$this->faker->randomDigitNotNull),
                md5($this->faker->password),
                $this->faker->randomNumber(3)
            )
        );

        $this->assertInstanceOf('Marmot\Framework\Interfaces\ICommandHandler', $commandHandler);
        $this->assertInstanceOf(
            'Member\CommandHandler\User\UpdatePasswordUserCommandHandler',
            $commandHandler
        );
    }
}
