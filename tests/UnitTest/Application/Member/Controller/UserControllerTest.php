<?php
namespace Member\Controller;

use System\Classes\Request;
use System\Classes\CommandBus;

use Member\Command\User\UpdatePasswordUserCommand;
use Member\Repository\User\UserRepository;
use Member\CommandHandler\User\UserCommandHandlerFactory;
use Member\Model\User;
use Member\View\UserView;
use Member\Utils\ObjectGenerate;

use tests\GenericTestCase;
use Prophecy\Argument;

class UserControllerTest extends GenericTestCase
{
    private $controller;

    public function setUp()
    {
        $this->controller = $this->getMockBuilder(UserController::class)
            ->setMethods(
                [
                    'getUserRepository',
                    'getCommandBus',
                    'getRequest',
                    'render',
                    'displayError'
                ]
            )->getMock();
    }

    public function tearDown()
    {
        unset($this->controller);
    }

    public function testCorrectExtendsController()
    {
        $controller = new UserController();
        $this->assertInstanceof('System\Classes\Controller', $controller);
    }

    /**
     * 测试更新密码成功
     * 1.
     */
    public function testUpdatePasswordSuccess()
    {
        $putData = array(
            'type'=>'users',
            'attributes'=>array(
                'oldPassword'=>'oldPassword',
                'password'=>'password'
            )
        );
        $request = $this->prophesize(Request::class);
        $request->put(Argument::exact('data'))
                ->shouldBeCalledTimes(1)
                ->willReturn($putData);

        $id = 1;
        $user = ObjectGenerate::generateUser($id);
        $userRepository = $this->prophesize(UserRepository::class);
        $userRepository->getOne(Argument::exact($id))
                       ->shouldBeCalledTimes(1)
                       ->willReturn($user);
        
        $commandBus = $this->prophesize(CommandBus::class);
        $commandBus->send(
            Argument::exact(
                new UpdatePasswordUserCommand(
                    'oldPassword',
                    'password',
                    $id
                )
            )
        )->shouldBeCalledTimes(1)
         ->willReturn(true);

        $this->controller->expects($this->once())
                   ->method('getUserRepository')
                   ->willReturn($userRepository->reveal());
        $this->controller->expects($this->once())
                   ->method('getCommandBus')
                   ->willReturn($commandBus->reveal());
        $this->controller->expects($this->once())
                   ->method('getRequest')
                   ->willReturn($request->reveal());
        $this->controller->expects($this->once())
                   ->method('render')
                   ->with(
                       $this->equalTo(
                           new UserView($user)
                       )
                   );
        $this->controller->expects($this->exactly(0))
                   ->method('displayError');

        $result = $this->controller->updatePassword($id);
        $this->assertTrue($result);
    }

    public function testUpdatePasswordFailure()
    {
    }
}
