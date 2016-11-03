<?php
namespace Member\CommandHandler\User;

use tests\GenericTestCase;
use Member\Command\User\SignUpUserCommand;
use Member\Command\User\UpdatePasswordUserCommand;

class UserCommandHandlerFactoryTest extends GenericTestCase
{

    private $stub;
    private $faker;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create('zh_CN');
        //初始化工厂桩件
        $this->stub = new UserCommandHandlerFactory();
    }

    public function testSignUpUserCommandHandler()
    {
        $commandHandler = $this->stub->getHandler(
            new SignUpUserCommand(
                $this->faker->phoneNumber,
                $this->faker->password
            )
        );

        $this->assertInstanceOf('System\Interfaces\ICommandHandler', $commandHandler);
        $this->assertInstanceOf('Member\CommandHandler\User\SignUpUserCommandHandler', $commandHandler);
    }

    public function testUpdatePasswordUserCommandHandler()
    {
        $commandHandler = $this->stub->getHandler(
            new UpdatePasswordUserCommand(
                md5($this->faker->password.$this->faker->randomDigitNotNull),
                md5($this->faker->password),
                $this->faker->randomNumber(3)
            )
        );

        $this->assertInstanceOf('System\Interfaces\ICommandHandler', $commandHandler);
        $this->assertInstanceOf(
            'Member\CommandHandler\User\UpdatePasswordUserCommandHandler',
            $commandHandler
        );
    }
}
