<?php
namespace Member\Model;

use Marmot\Core;
use Member\Model\User;
use Member\Repository\User\UserRepository;

use tests\GenericTestCase;
use Prophecy\Argument;

/**
 * Member\Model\User.class.php 测试文件
 * @author chloroplast
 * @version 1.0.0:2016.04.19
 */

class UserTest extends GenericTestCase
{

    private $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function tearDown()
    {
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

//    public function testVerifyPasswordSucess()
//    {
//        $oldPassword = 'oldPassword';
//        $salt = 'salt';
//
//        $this->user->encryptPassword($oldPassword, $salt);
//
//        $verifyPasswordMethod = $this->getPrivateMethod(
//            'Member\Model\User',
//            'verifyPassword'
//        );
//        $result = $verifyPasswordMethod->invoke($this->user, $oldPassword);
//        $this->assertTrue($result);
//    }
//
//    public function testVerifyPasswordFailure()
//    {
//        $oldPassword = 'oldPassword';
//        $newPassword = 'newPassword';
//        $salt = 'salt';
//
//        $this->user->encryptPassword($oldPassword, $salt);
//
//        $verifyPasswordMethod = $this->getPrivateMethod(
//            'Member\Model\User',
//            'verifyPassword'
//        );
//        $result = $verifyPasswordMethod->invoke($this->user, $newPassword);
//        $this->assertFalse($result);
//        $this->assertEquals(USER_OLD_PASSWORD_NOT_CORRECT, Core::getLastError()->getId());
//    }
//    
//    public function testUpdatePasswordSucess()
//    {
//        $password = 'password';
//        $this->user->setPassword($password);
//
//        $this->user = $this->getMockBuilder(User::class)
//                           ->setMethods(['encryptPassword', 'getUserRepository'])
//                           ->getMock();
//
//        $this->user->expects($this->once())
//            ->method('encryptPassword')
//            ->with($this->equalTo($password));
//
//        $repository = $this->prophesize(UserRepository::class);
//        
//        $repository->update(
//            Argument::exact($this->user),
//            Argument::exact(array('updateTime', 'password', 'salt'))
//        )->shouldBeCalledTimes(1)
//         ->willReturn(true);
//
//        $this->user->expects($this->once())
//                   ->method('getUserRepository')
//                   ->willReturn($repository->reveal());
//
//        $updatePasswordMethod = $this->getPrivateMethod(
//            'Member\Model\User',
//            'updatePassword'
//        );
//        $result = $updatePasswordMethod->invoke($this->user, $password);
//        $this->assertTrue($result);
//    }
//
//    public function testUpdatePassworFailure()
//    {
//        $password = 'password';
//        $this->user->setPassword($password);
//
//        $this->user = $this->getMockBuilder(User::class)
//                           ->setMethods(['encryptPassword','getUserRepository'])
//                           ->getMock();
//
//        $this->user->expects($this->once())
//            ->method('encryptPassword')
//            ->with($this->equalTo($password));
//
//        $repository = $this->prophesize(UserRepository::class);
//        
//        $repository->update(
//            Argument::exact($this->user),
//            Argument::exact(array('updateTime', 'password', 'salt'))
//        )->shouldBeCalledTimes(1)
//         ->willReturn(false);
//
//        $this->user->expects($this->once())
//                   ->method('getUserRepository')
//                   ->willReturn($repository->reveal());
//
//        $updatePasswordMethod = $this->getPrivateMethod(
//            'Member\Model\User',
//            'updatePassword'
//        );
//        $result = $updatePasswordMethod->invoke($this->user, $password);
//        $this->assertFalse($result);
//    }
}
