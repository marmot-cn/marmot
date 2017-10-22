<?php
namespace Member\CommandHandler\User;

use tests\GenericTestCase;
use System\Interfaces\ICommand;
use Marmot\Core;

use Member\Model\User;
use Member\Utils\ObjectGenerate;
use Member\Command\User\SignUpUserCommand;

use Prophecy\Argument;

/**
 * Member/CommandHandler/User/SignUpUserCommandHandler.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160828
 */

class SignUpUserCommandHandlerTest extends GenericTestCase
{

    public function setUp()
    {
        //这里不构建初始数据,只是在最后清理数据
        $this->commandHandler = new SignUpUserCommandHandler();
    }

    public function testCorrectImplementsICommandHandler()
    {
        $this->assertInstanceOf(
            'System\Interfaces\ICommandHandler',
            $this->commandHandler
        );
    }

    public function testConstructor()
    {
        $userParameter = $this->getPrivateProperty(
            'Member\CommandHandler\User\SignUpUserCommandHandler',
            'user'
        );
        $this->assertInstanceOf(
            'Member\Model\User',
            $userParameter->getValue($this->commandHandler)
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
        $faker->seed(1);

        $phoneNumber = $faker->phoneNumber;
        $password = $faker->password;

        $command = new SignUpUserCommand(
            $phoneNumber,
            $password
        );

        $user = $this->prophesize(User::class);
        $user->setCellPhone(
            Argument::exact($phoneNumber)
        )->shouldBeCalledTimes(1);
        $user->setUserName(
            Argument::exact($phoneNumber)
        )->shouldBeCalledTimes(1);
        $user->encryptPassword(
            Argument::exact($password)
        )->shouldBeCalledTimes(1);
        $user->signUp()->shouldBeCalledTimes(1)->willReturn(false);
        $user->getId()->shouldNotBeCalled();

        $this->commandHandler = $this->getMockBuilder(SignUpUserCommandHandler::class)
                                     ->setMethods(['getUser'])
                                     ->getMock();
        $this->commandHandler->expects($this->once())
                             ->method('getUser')
                             ->willReturn($user->reveal());
        

        $result = $this->commandHandler->execute($command);
        $this->assertFalse($result);
    }

    public function testExecuteSuccess()
    {
        $faker = \Faker\Factory::create('zh_CN');
        $faker->seed(1);

        $phoneNumber = $faker->phoneNumber;
        $password = $faker->password;
        $uid = $faker->randomNumber(3);

        $command = new SignUpUserCommand(
            $phoneNumber,
            $password
        );

        $user = $this->prophesize(User::class);
        $user->setCellPhone(
            Argument::exact($phoneNumber)
        )->shouldBeCalledTimes(1);
        $user->setUserName(
            Argument::exact($phoneNumber)
        )->shouldBeCalledTimes(1);
        $user->encryptPassword(
            Argument::exact($password)
        )->shouldBeCalledTimes(1);
        $user->signUp()->shouldBeCalledTimes(1)->willReturn(true);
        $user->getId()->shouldBeCalledTimes(1)->willReturn($uid);

        $this->commandHandler = $this->getMockBuilder(SignUpUserCommandHandler::class)
                                     ->setMethods(['getUser'])
                                     ->getMock();
        $this->commandHandler->expects($this->once())
                             ->method('getUser')
                             ->willReturn($user->reveal());
        

        $result = $this->commandHandler->execute($command);
        $this->assertTrue($result);
        $this->assertEquals($uid, $command->uid);
    }
}
