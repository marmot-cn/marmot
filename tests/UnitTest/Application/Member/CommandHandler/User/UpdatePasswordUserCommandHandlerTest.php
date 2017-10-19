<?php
namespace Member\CommandHandler\User;

use tests\GenericTestsDatabaseTestCase;
use System\Interfaces\ICommand;
use Marmot\Core;

use Member\Model\User;
use Member\Utils\ObjectGenerate;
use Member\Command\User\UpdatePasswordUserCommand;

/**
 * Member/CommandHandler/User/UpdatePasswordUserCommandHandler.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160828
 */

class UpdatePasswordUserCommandHandlerTest extends GenericTestsDatabaseTestCase
{

    public $fixtures = array(
        'pcore_user',
    );

    public function setUp()
    {
        //这里不构建初始数据,只是在最后清理数据
        $this->commandHandler = new UpdatePasswordUserCommandHandler();
    }

    public function tearDown()
    {
        Core::$cacheDriver->flushAll();
        parent::tearDown();
    }

    public function testCorrectImplementsICommandHandler()
    {
        $this->assertInstanceOf(
            'System\Interfaces\ICommandHandler',
            $this->commandHandler
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

    public function testExecuteNotExistUser()
    {
        $faker = \Faker\Factory::create('zh_CN');
        $faker->seed(0);//设置seed,放置和生成数据相同

        $command = new UpdatePasswordUserCommand(
            $faker->password,
            $faker->password,
            1
        );

        $result = $this->commandHandler->execute($command);
        $this->assertFalse($result);
        $this->assertEquals(RESOURCE_NOT_EXIST, Core::getLastError()->getId());
    }

    public function testExecuteIncorrectOldPassword()
    {
        $faker = \Faker\Factory::create('zh_CN');
        $faker->seed(0);//设置seed,放置和生成数据相同
        $oldPassword = '123456';
        $repository = Core::$container->get('Member\Repository\User\UserRepository');
        
        //构建一个用户密码为$oldPassword
        $user = ObjectGenerate::generateUser(0, 0, array('password'=> $oldPassword));
        $repository->add($user);

        $command = new UpdatePasswordUserCommand(
            $oldPassword.'different',
            $faker->password,
            $user->getId()
        );

        $result = $this->commandHandler->execute($command);
        $this->assertFalse($result);
        $this->assertEquals(USER_OLD_PASSWORD_NOT_CORRECT, Core::getLastError()->getId());
    }

    public function testExecute()
    {
        $faker = \Faker\Factory::create('zh_CN');
        $oldPassword = '123456';
        $newPassword = $faker->password;
        $repository = Core::$container->get('Member\Repository\User\UserRepository');

        //构建一个用户密码为$oldPassword
        $user = ObjectGenerate::generateUser(0, 0, array('password'=> $oldPassword));
        $repository->add($user);

        $command = new UpdatePasswordUserCommand(
            $oldPassword,
            $newPassword,
            $user->getId()
        );

        $result = $this->commandHandler->execute($command);
        $this->assertTrue($result);
        //检查密码是否修改正确
        $user = $repository->getOne($user->getId());
        $testUser = new User();
        //使用相同的盐 和 新密码
        $testUser->encryptPassword($newPassword, $user->getSalt());
        $this->assertEquals($user->getPassword(), $testUser->getPassword());
    }
}
