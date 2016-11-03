<?php
namespace Member\Command\User;

use tests\GenericTestCase;

/**
 * Member/Command/User/SignUpUserCommand.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160828
 */

class SignUpUserCommandTest extends GenericTestCase
{
    private $stub;

    //生成随机数据的种子
    private $fakerSeed = 1000;

    private $fakerData = array();

    public function setUp()
    {
        $faker = \Faker\Factory::create('zh_CN');
        //保证每次生成数据一致
        $faker->seed($this->fakerSeed);
        $this->fakerData = array(
                                'cellPhone' => $faker->phoneNumber,
                                'password' => $faker->password,
                                'uid' => $faker->randomNumber(3)
                            );
        $this->stub = new SignUpUserCommand(
            $this->fakerData['cellPhone'],
            $this->fakerData['password'],
            $this->fakerData['uid']
        );
    }

    public function testCorrectInstanceExtendsCommand()
    {
        $this->assertInstanceof('System\Interfaces\ICommand', $this->stub);
    }

    public function testPasswordParameter()
    {
        $this->assertEquals($this->fakerData['password'], $this->stub->password);
    }

    public function testCellphoneParameter()
    {
        $this->assertEquals($this->fakerData['cellPhone'], $this->stub->cellPhone);
    }

    public function testUidParameter()
    {
        $this->assertEquals($this->fakerData['uid'], $this->stub->uid);
    }

    public function testDefaultUidParameter()
    {
        $command = $this->getMockBuilder('Member\Command\User\SignUpUserCommand')
                           ->setConstructorArgs(
                               array(
                                        $this->fakerData['cellPhone'],
                                        $this->fakerData['password']
                                    )
                           )
                           ->getMockForAbstractClass();

        $this->assertEquals(0, $command->uid);
    }
}
