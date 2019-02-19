<?php
namespace Member\Controller;

use Marmot\Framework\Classes\Request;
use Marmot\Framework\Classes\CommandBus;

use Application\WidgetRules;

use Member\Command\User\SignUpUserCommand;
use Member\Command\User\UpdatePasswordUserCommand;
use Member\Repository\User\UserRepository;
use Member\CommandHandler\User\UserCommandHandlerFactory;
use Member\Model\User;
use Member\Model\NullUser;
use Member\View\UserView;
use Member\Utils\ObjectGenerate;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class UserControllerTest extends TestCase
{
    private $controller;
    private $childController;
    private $request;

    public function setUp()
    {
        $this->controller = $this->getMockBuilder(UserController::class)
            ->setMethods(
                [
                    'getUserRepository',
                    'getCommandBus',
                    'getRequest',
                    'render',
                    'displayError',
                    'validateGetOneScenario',
                    'validateSignUpScenario',
                    'formatParameters'
                ]
            )->getMock();

        $this->childController = new class extends UserController{
            public function getUserRepository() : UserRepository
            {
                return parent::getUserRepository();
            }
            public function getCommandBus() : CommandBus
            {
                return parent::getCommandBus();
            }

            public function validateGetOneScenario(int $id)
            {
                return parent::validateGetOneScenario($id);
            }

            public function validateSignUpScenario()
            {
                return parent::validateSignUpScenario();
            }
        };

        $this->request = $this->prophesize(Request::class);
    }

    public function tearDown()
    {
        unset($this->controller);
        unset($this->childController);
    }

    public function testCorrectExtendsController()
    {
        $controller = new UserController();
        $this->assertInstanceof('Marmot\Framework\Classes\Controller', $controller);
    }

    public function testGetUserRepository()
    {
        $this->assertInstanceof(
            'Member\Repository\User\UserRepository',
            $this->childController->getUserRepository()
        );
    }

    public function testGetCommandBus()
    {
        $this->assertInstanceof(
            'Marmot\Framework\Classes\CommandBus',
            $this->childController->getCommandBus()
        );
    }

    public function testValidateGetOneScenario()
    {
        $id = 1;

        $widgetRules = $this->prophesize(WidgetRules::class);

        $result = $this->childController->validateGetOneScenario($id);

        $this->assertFalse($result);
    }


    public function testValidateSignUpScenario()
    {
        $widgetRules = [];

        $result = $this->childController->validateSignUpScenario();

        $this->assertFalse($result);
    }

    public function testGetOneParFailure()
    {
        $id = 0;
        
        $this->controller->expects($this->exactly(1))
            ->method('validateGetOneScenario')
            ->willReturn(false);

        $result = $this->controller->getOne($id);
        $this->assertFalse($result);
    }


    private function initialGetOne($user)
    {

        $this->controller->expects($this->any())
            ->method('validateGetOneScenario')
            ->willReturn(true);

        $id = 1;

        $this->repository = $this->prophesize(UserRepository::class);
        // $this->repository->getOne(Argument::exact($id))
        //            ->shouldBeCalledTimes(1)
        //            ->willReturn($user);

        $this->controller = $this->getMockBuilder(UserController::class)
                                 ->setMethods(
                                     [
                                        'getUserRepository',
                                        'renderView',
                                        'displayError'
                                     ]
                                 )
                                 ->getMock();

        $this->controller->expects($this->any())
                    ->method('getUserRepository')
                     ->willReturn($this->repository->reveal());

        return $this->controller;
    }

    public function testGetOneSuccess()
    {
        $user = $this->prophesize(User::class);
        $this->initialGetOne($user);

        $this->controller->expects($this->any())
            ->method('renderView')
            ->willReturn(true);

        $id = 1;

        $result = $this->controller->getOne($id);
        $this->assertFalse($result);
    }

    public function testGetOneFailure()
    {
        $user = $this->prophesize(NullUser::class);
        $this->initialGetOne($user);

        $this->controller->expects($this->any())
            ->method('displayError')
            ->willReturn(true);

        $id = 1;

        $result = $this->controller->getOne($id);
        $this->assertFalse($result);
    }

    private function initialFetchList(array $userlList)
    {
        $ids = [1,2];

        $this->repository = $this->prophesize(UserRepository::class);
        $this->repository->getList(Argument::exact($ids))
                   ->shouldBeCalledTimes(1)
                   ->willReturn($userlList);
                   
        $this->controller = $this->getMockBuilder(UserController::class)
                                 ->setMethods(
                                     [
                                        'getUserRepository',
                                        'renderView',
                                        'displayError'
                                     ]
                                 )
                                 ->getMock();

        $this->controller->expects($this->once())
                    ->method('getUserRepository')
                    ->willReturn($this->repository->reveal());

        return $this->controller;
    }

    public function testSignUpValidateFailure()
    {
        $postData = array(
            'type'=>'users',
            'attributes'=>array(
                'cellphone'=>'cellphone',
                'password'=>'password'
            )
        );

        $this->controller->expects($this->exactly(1))
            ->method('validateSignUpScenario')
            ->willReturn(false);

        $result = $this->controller->signUp();
        $this->assertFalse($result);
    }

    public function testSignUpCommandExecuteFailure()
    {
        $postData = array(
            'type'=>'users',
            'attributes'=>array(
                'cellphone'=>'cellphone',
                'password'=>'password'
            )
        );

        $this->controller->expects($this->exactly(1))
            ->method('validateSignUpScenario')
            ->willReturn(true);

        $request = $this->prophesize(Request::class);
        $request->post(Argument::exact('data'))
                ->shouldBeCalledTimes(1)
                ->willReturn($postData);

        $this->controller->expects($this->once())
                   ->method('getRequest')
                   ->willReturn($request->reveal());
        $this->controller->expects($this->exactly(0))
                   ->method('render');
        $this->controller->expects($this->once())
                   ->method('displayError');

        $result = $this->controller->signUp();
        $this->assertFalse($result);
    }

    public function testSignInTypeFailure()
    {
        $postData = array(
            'type'=>'test',
            'attributes'=>array(
                'cellphone'=>'cellphone',
                'password'=>'password'
            )
        );

        $this->controller->expects($this->once())
            ->method('displayError')
            ->willReturn(false);

        $result = $this->controller->signIn();
        $this->assertFalse($result);
    }

    public function testUpdatePasswordCommandExecuteFailure()
    {
        $putData = array(
            'type'=>'users',
            'attributes'=>array(
                'oldPassword'=>'oldPassword',
                'password'=>'password'
            )
        );
        $id = 1;

        $request = $this->prophesize(Request::class);
        $request->put(Argument::exact('data'))
                ->shouldBeCalledTimes(1)
                ->willReturn($putData);

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
         ->willReturn(false);

        $this->controller->expects($this->exactly(0))
                   ->method('getUserRepository');
        $this->controller->expects($this->once())
                   ->method('getCommandBus')
                   ->willReturn($commandBus->reveal());
        $this->controller->expects($this->once())
                   ->method('getRequest')
                   ->willReturn($request->reveal());
        $this->controller->expects($this->exactly(0))
                   ->method('render');
        $this->controller->expects($this->once())
                   ->method('displayError');

        $result = $this->controller->updatePassword($id);
        $this->assertFalse($result);
    }

    public function testUpdatePasswordNotExistUser()
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
                       ->willReturn(new NullUser());

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
        $this->controller->expects($this->exactly(0))
                   ->method('render');
        $this->controller->expects($this->once())
                   ->method('displayError');

        $result = $this->controller->updatePassword($id);
        $this->assertFalse($result);
    }

    /**
     * 测试更新密码成功
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

//    public function testUpdatePasswordFailure()
//    {
//    }
}
