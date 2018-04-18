<?php
namespace Member\Model;

use Marmot\Core;
use Member\Model\User;
use Member\Repository\User\UserRepository;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * Member\Model\User.class.php 测试文件
 * @author chloroplast
 * @version 1.0.0:2016.04.19
 */

class UserTest extends TestCase
{

    private $user;
    private $childUser;

    public function setUp()
    {
        $this->user = new User();
        $this->childUser = new class extends User{
            public function getUserRepository() : UserRepository
            {
                return parent::getUserRepository();
            }
        };
    }

    public function tearDown()
    {
        Core::setLastError(ERROR_NOT_DEFINED);
        unset($this->user);
        parent::tearDown();
    }

    /**
     * User 网店用户领域对象,测试构造函数
     */
    public function testUserConstructor()
    {
        //测试初始化用户状态
        $this->assertEquals(User::STATUS_NORMAL, $this->user->getStatus());
        $this->assertEquals('', $this->user->getRealName());
    }

    /**
     * 检测是否正确继承 Member\Model\User
     */
    public function testCorrectExtendsUser()
    {
        $this->assertInstanceof('Member\Model\User', $this->user);
    }

    //realName 测试 ---------------------------------------------------- start
    /**
     * 设置 BankAccount setrealName() 正确的传参类型,期望传值正确
     */
    public function testSetRealNameCorrectType()
    {
        $this->user->setRealName('string');
        $this->assertEquals('string', $this->user->getRealName());
    }

    /**
     * 设置 BankAccount setRealName() 错误的传参类型,期望期望抛出TypeError exception
     *
     * @expectedException TypeError
     */
    public function testSetRealNameWrongType()
    {
        $this->user->setRealName(array(1,2,3));
    }
    //realName 测试 ----------------------------------------------------   end

    //status 测试 ------------------------------------------------------ start
    /**
     * 循环测试 User setStatus() 是否符合预定范围
     *
     * @dataProvider statusProvider
     */
    public function testSetStatus($actual, $expected)
    {
        $this->user->setStatus($actual);
        $this->assertEquals($expected, $this->user->getStatus());
    }

    /**
     * 循环测试 User setStatus() 数据构建器
     */
    public function statusProvider()
    {
        return array(
            array(User::STATUS_NORMAL,User::STATUS_NORMAL),
            array(User::STATUS_DELETE,User::STATUS_DELETE),
            array(999,User::STATUS_NORMAL),
        );
    }

    /**
     * 设置 User setStatus() 错误的传参类型,期望期望抛出TypeError exception
     *
     * @expectedException TypeError
     */
    public function testSetStatusWrongType()
    {
        $this->user->setStatus('string');
    }
    //status 测试 ------------------------------------------------------   end
    
    public function testIsNormal()
    {
        $this->user->setStatus(User::STATUS_NORMAL);
        $this->assertTrue($this->user->isNormal());
        $this->assertFalse($this->user->isDelete());
    }

    public function testIsDelete()
    {
        $this->user->setStatus(User::STATUS_DELETE);
        $this->assertTrue($this->user->isDelete());
        $this->assertFalse($this->user->isNormal());
    }

    public function testGetUserRepository()
    {
        $this->assertInstanceof(
            'Member\Repository\User\UserRepository',
            $this->childUser->getUserRepository()
        );
    }

    public function testSignUpSucess()
    {
        $this->user = $this->getMockBuilder(User::class)
                           ->setMethods(['getUserRepository'])
                           ->getMock();

        $repository = $this->prophesize(UserRepository::class);
        $repository->add(Argument::exact($this->user))
                   ->shouldBeCalledTimes(1)
                   ->willReturn(true);

        $this->user->expects($this->once())
                   ->method('getUserRepository')
                   ->willReturn($repository->reveal());

        $result = $this->user->signUp();
        $this->assertTrue($result);
    }

    public function testSignUpFailure()
    {
        $this->user = $this->getMockBuilder(User::class)
                           ->setMethods(['getUserRepository'])
                           ->getMock();

        $repository = $this->prophesize(UserRepository::class);
        $repository->add(Argument::exact($this->user))
                   ->shouldBeCalledTimes(1)
                   ->willReturn(false);

        $this->user->expects($this->once())
                   ->method('getUserRepository')
                   ->willReturn($repository->reveal());

        $result = $this->user->signUp();
        $this->assertFalse($result);
        $this->assertEquals(USER_IDENTIFY_DUPLICATE, Core::getLastError()->getId());
    }

    /**
     * 编写思路, 我要修改密码, 首先
     * 1. 要检测用户密码是否正确, 即我给一个错误的旧密码,
     *    然后期望返回失败, 并返回错误编号
     * 2. 因为我的检测密码在先, 所以我就不应该再去处理修改密码的动作
     *    即我不希望getUserRepository()这个函数被调用, 也就意味着不对数据库做操作
     * 3. 最后我断言结果返回失败, 因为我本身就给了不匹配的密码
     */
    public function testChangePasswordVerifyFail()
    {
        $this->user = $this->getMockBuilder(User::class)
                           ->setMethods(['getUserRepository'])
                           ->getMock();

        $this->user->expects($this->exactly(0))
                    ->method('getUserRepository');

        $this->user->setPassword('encryptPassword');
        $this->user->setSalt('salt');
        $result = $this->user->changePassword('oldPassword', 'newPassword');
        $this->assertFalse($result);
        $this->assertEquals(USER_OLD_PASSWORD_NOT_CORRECT, Core::getLastError()->getId());
    }
    
    /**
     * 编写思路, 我这次要给出正确的旧密码, 也就是期望在更新密码的过程失败
     * 1. 我这次给出正确的密码, 期望getUserRepository()这个函数被调用, 且被调用一次
     * 2. 我mock userRepository, 我预测
     *    2.1 接收调用方对象(即$this, user对象自己)
     *    2.2 接收updateTime,password,salt
     * 3. 假设 userRepository 返回 false
     * 4. 我们断言返回 false, 且 lastError 没有被设置, 即还是 ERROR_NOT_DEFINED
     */
    public function testChangePasswordUpdateFail()
    {
        $this->user = $this->getMockBuilder(User::class)
                           ->setMethods(['getUserRepository'])
                           ->getMock();

        $this->user->encryptPassword('oldPassword', 'salt');

        $repository = $this->prophesize(UserRepository::class);
        $repository->update(
            Argument::exact($this->user),
            Argument::exact(array('updateTime', 'password', 'salt'))
        )->shouldBeCalledTimes(1)
         ->willReturn(false);

        $this->user->expects($this->once())
                   ->method('getUserRepository')
                   ->willReturn($repository->reveal());

        $result = $this->user->changePassword('oldPassword', 'salt');

        $this->assertFalse($result);
        $this->assertEquals(ERROR_NOT_DEFINED, Core::getLastError()->getId());
    }

    public function testUpdatePasswordSucess()
    {
        $this->user = $this->getMockBuilder(User::class)
                           ->setMethods(['getUserRepository'])
                           ->getMock();

        $this->user->encryptPassword('oldPassword', 'salt');

        $repository = $this->prophesize(UserRepository::class);
        $repository->update(
            Argument::exact($this->user),
            Argument::exact(array('updateTime', 'password', 'salt'))
        )->shouldBeCalledTimes(1)
         ->willReturn(true);

        $this->user->expects($this->once())
                   ->method('getUserRepository')
                   ->willReturn($repository->reveal());

        $result = $this->user->changePassword('oldPassword', 'salt');

        $this->assertTrue($result);
    }
}
