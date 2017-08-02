<?php
namespace User\Model;

use Marmot\Core;
use tests\GenericTestCase;

use Member\Model\User;

/**
 * User\Model\User.class.php 测试文件
 * @author chloroplast
 * @version 1.0.0:2016.04.19
 */

class UserTest extends GenericTestCase
{
    private $user;

    public function setUp()
    {
        $this->user = $this->getMockBuilder('User\Model\User')
                      ->getMockForAbstractClass();
    }

    public function testImplementsIObject()
    {
        $this->assertInstanceof("Marmot\Common\Model\IObject", $this->user);
    }

    /**
     * User 网店用户领域对象,测试构造函数
     */
    public function testUserConstructor()
    {
        //测试初始化网店用户id
        $idParameter = $this->getPrivateProperty('User\Model\User', 'id');
        $this->assertEquals(0, $idParameter->getValue($this->user));
        $this->assertEquals(0, $this->user->getId());

        //测试初始化用户手机号
        $cellPhoneParameter = $this->getPrivateProperty('User\Model\User', 'cellPhone');
        $this->assertEmpty($cellPhoneParameter->getValue($this->user));
        $this->assertEmpty($this->user->getCellPhone());

        //测试初始化昵称
        $nickNameParameter = $this->getPrivateProperty('User\Model\User', 'nickName');
        $this->assertEmpty($nickNameParameter->getValue($this->user));
        $this->assertEmpty($this->user->getNickName());

        //测试初始化用户名预留字段
        $userNameParameter = $this->getPrivateProperty('User\Model\User', 'userName');
        $this->assertEmpty($userNameParameter->getValue($this->user));
        $this->assertEmpty($this->user->getUserName());

        //测试初始化用户密码
        $passwordParameter = $this->getPrivateProperty('User\Model\User', 'password');
        $this->assertEmpty($passwordParameter->getValue($this->user));
        $this->assertEmpty($this->user->getPassword());

        //测试初始化注册时间
        $createTimeParameter = $this->getPrivateProperty('User\Model\User', 'createTime');
        $this->assertGreaterThan(0, $createTimeParameter->getValue($this->user));
        $this->assertEquals($createTimeParameter->getValue($this->user), $this->user->getCreateTime());

        //测试初始化更新时间
        $updateTimeParameter = $this->getPrivateProperty('User\Model\User', 'updateTime');
        $this->assertEquals(0, $updateTimeParameter->getValue($this->user));
        $this->assertEquals($updateTimeParameter->getValue($this->user), $this->user->getUpdateTime());

        //测试初始化更新时间
        $statusTimeParameter = $this->getPrivateProperty('User\Model\User', 'statusTime');
        $this->assertEquals(0, $statusTimeParameter->getValue($this->user));
        $this->assertEquals($statusTimeParameter->getValue($this->user), $this->user->getStatusTime());
    }

    //cellPhone 测试 --------------------------------------------------- start
    /**
     * 设置 User setCellPhone() 正确的传参类型,期望传值正确
     */
    public function testSetCellPhoneCorrectType()
    {
        $this->user->setCellPhone('15202939435');
        $this->assertEquals('15202939435', $this->user->getCellPhone());
    }
    
    /**
     * 设置 User setCellPhone() 正确的传参类型,但是不属于手机格式,期望返回空.
     */
    public function testSetCellPhoneCorrectTypeButNotEmail()
    {
        $this->user->setCellPhone('15202939435'.'a');
        $this->assertEquals('', $this->user->getCellPhone());
    }
    //cellPhone 测试 ---------------------------------------------------   end

    //nickName 测试 ---------------------------------------------------- start
    /**
     * 设置 User setNickName() 正确的传参类型,期望传值正确
     */
    public function testSetNickNameCorrectType()
    {
        $this->user->setNickName('string');
        $this->assertEquals('string', $this->user->getNickName());
    }

    /**
     * 设置 User setNickName() 错误的传参类型,期望期望抛出TypeError exception
     *
     * @expectedException TypeError
     */
    public function testSetNickNameWrongType()
    {
        $this->user->setNickName(array(1,2,3));
    }
    //nickName 测试 ----------------------------------------------------   end

    //userName 测试 ---------------------------------------------------- start
    /**
     * 设置 User setUserName() 正确的传参类型,期望传值正确
     */
    public function testSetUserNameCorrectType()
    {
        $this->user->setUserName('string');
        $this->assertEquals('string', $this->user->getUserName());
    }

    /**
     * 设置 User setUserName() 错误的传参类型,期望期望抛出TypeError exception
     *
     * @expectedException TypeError
     */
    public function testSetUserNameWrongType()
    {
        $this->user->setUserName(array(1,2,3));
    }
    //userName 测试 ----------------------------------------------------   end

    //password 测试 ---------------------------------------------------- start
    /**
     * 设置 User setPassword() 正确的传参类型,期望传值正确
     */
    public function testSetPasswordCorrectType()
    {
        $this->user->setPassword('string');
        $this->assertEquals('string', $this->user->getPassword());
    }

    /**
     * 设置 User setPassword() 错误的传参类型,期望期望抛出TypeError exception
     *
     * @expectedException TypeError
     */
    public function testSetPasswordWrongType()
    {
        $this->user->setPassword(array(1,2,3));
    }
    //password 测试 ----------------------------------------------------   end

    //encryptPassword 测试 ---------------------------------------------  start
    /**
     * 设置User encryptPassword() salt传空,期望产生salt值和加密过的密码
     */
    public function testUserEncryptPasswordWithoutSalt()
    {
        //初始化密码
        $password = '111111';
        $this->user->encryptPassword($password);

        //确认密码是一个32位长度和salt一起加密过的md5值
        $this->assertEquals(32, strlen($this->user->getPassword()));

        //确认盐是一个4位长度
        $this->assertEquals(4, strlen($this->user->getSalt()));
    }

    /**
     * 设置User encryptPassword()
     *
     * 1. 先生成密码和salt
     * 2. 传入salt和原始密码,确认再次加密后的值和第一次生成的密码一致
     */
    public function testUserEncryptPasswordWithSalt()
    {
        //初始化密码
        $password = '111111';
        $this->user->encryptPassword($password);
        $salt = $this->user->getSalt();

        //初始化一个新的用户,再次加密
        $anotherUser = $this->getMockBuilder('User\Model\User')
                            ->getMockForAbstractClass();
        $anotherUser->encryptPassword($password, $salt);

        //校验第一次生成的密码和盐,再次加密期望一致
        $this->assertEquals($this->user->getPassword(), $anotherUser->getPassword());
    }
    //encryptPassword 测试 ----------------------------------------------  end

    //salt 测试 -------------------------------------------------------- start
    /**
     * 设置 User setSalt() 正确的传参类型,期望传值正确
     */
    public function testSetSaltCorrectType()
    {
        $this->user->setSalt('string');
        $this->assertEquals('string', $this->user->getSalt());
    }

    /**
     * 设置 User setSalt() 错误的传参类型,期望期望抛出TypeError exception
     *
     * @expectedException TypeError
     */
    public function testSetSaltWrongType()
    {
        $this->user->setSalt(array(1,2,3));
    }
    //salt 测试 --------------------------------------------------------   end
}
