<?php
namespace Member\CommandHandler\User;

use tests\GenericTestsDatabaseTestCase;
use System\Interfaces\ICommand;
use Marmot\Core;

use Member\Model\User;
use Member\Utils\ObjectGenerate;
use Member\Command\User\SignUpUserCommand;

/**
 * Member/CommandHandler/User/SignUpUserCommandHandler.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160828
 */

class SignUpUserCommandHandlerTest extends GenericTestsDatabaseTestCase
{

    public $fixtures = array(
        'pcore_user',
    );

    public function setUp()
    {
        //这里不构建初始数据,只是在最后清理数据
        $this->stub = new SignUpUserCommandHandler();
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
            $this->stub
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArgumentException()
    {
        $command = new class implements ICommand {
        };
        $this->stub->execute($command);
    }

    public function testExecute()
    {
        $faker = \Faker\Factory::create('zh_CN');
        $faker->seed($seed);//设置seed,放置和生成数据相同

        $command = new SignUpUserCommand(
            $faker->phoneNumber,
            $faker->password
        );
        $result = $this->stub->execute($command);

        $this->assertTrue($result);
        $this->assertNotEmpty($command->uid);

        $repository = Core::$container->get('Member\Repository\User\UserRepository');
        $user = $repository->getOne($command->uid);

        $this->assertEquals($user->getCellPhone(), $command->cellPhone);
        $this->assertEquals($user->getid(), $command->uid);

        $testUser = new User();
        $testUser->encryptPassword($command->password, $user->getSalt());
        $this->assertEquals($user->getPassword(), $testUser->getPassword());
    }
}
