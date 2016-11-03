<?php
namespace Member\Command\User;

use tests\GenericTestCase;

/**
 * Member/Command/User/UpdatePasswordUserCommand.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160828
 */

class UpdatePasswordUserCommandTest extends GenericTestCase
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
                        'oldPassword' => $faker->password,
                        'password' => $faker->password.$faker->randomDigit,
                        'uid' => $faker->randomDigit,
                        );
        $this->stub = new UpdatePasswordUserCommand(
            $this->fakerData['oldPassword'],
            $this->fakerData['password'],
            $this->fakerData['uid']
        );
    }

    public function testCorrectInstanceExtendsCommand()
    {
        $this->assertInstanceof('System\Interfaces\ICommand', $this->stub);
    }

    public function testOldPasswordParameter()
    {
        $this->assertEquals($this->fakerData['oldPassword'], $this->stub->oldPassword);
    }

    public function testPasswordParameter()
    {
        $this->assertEquals($this->fakerData['password'], $this->stub->password);
    }

    public function testUidParameter()
    {
        $this->assertEquals($this->fakerData['uid'], $this->stub->uid);
    }
}
