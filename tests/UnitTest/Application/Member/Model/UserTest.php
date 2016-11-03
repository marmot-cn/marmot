<?php
namespace Member\Model;

use Marmot\Core;
use tests\GenericTestCase;

/**
 * Member\Model\User.class.php 测试文件
 * @author chloroplast
 * @version 1.0.0:2016.04.19
 */

class UserTest extends GenericTestCase
{

    private $stub;

    public function setUp()
    {
        $this->stub = new User();
    }

    public function tearDown()
    {
        unset($this->stub);
        parent::tearDown();
    }

    /**
     * User 网店用户领域对象,测试构造函数
     */
    public function testUserConstructor()
    {
        //测试初始化用户状态
        $statusParameter = $this->getPrivateProperty('Member\Model\User', 'status');
        $this->assertEquals(STATUS_NORMAL, $statusParameter->getValue($this->stub));

        $realNameParameter = $this->getPrivateProperty('Member\Model\User', 'realName');
        $this->assertEquals('', $realNameParameter->getValue($this->stub));
    }

    /**
     * 检测是否正确继承 Member\Model\User
     */
    public function testCorrectExtendsUser()
    {
        $this->assertInstanceof('Member\Model\User', $this->stub);
    }

    //realName 测试 ---------------------------------------------------- start
    /**
     * 设置 BankAccount setrealName() 正确的传参类型,期望传值正确
     */
    public function testSetRealNameCorrectType()
    {
        $this->stub->setRealName('string');
        $this->assertEquals('string', $this->stub->getRealName());
    }

    /**
     * 设置 BankAccount setRealName() 错误的传参类型,期望期望抛出TypeError exception
     *
     * @expectedException TypeError
     */
    public function testSetRealNameWrongType()
    {
        $this->stub->setRealName(array(1,2,3));
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
        $this->stub->setStatus($actual);
        $this->assertEquals($expected, $this->stub->getStatus());
    }

    /**
     * 循环测试 User setStatus() 数据构建器
     */
    public function statusProvider()
    {
        return array(
            array(STATUS_NORMAL,STATUS_NORMAL),
            array(STATUS_DELETE,STATUS_DELETE),
            array(999,STATUS_NORMAL),
        );
    }

    /**
     * 设置 User setStatus() 错误的传参类型,期望期望抛出TypeError exception
     *
     * @expectedException TypeError
     */
    public function testSetStatusWrongType()
    {
        $this->stub->setStatus('string');
    }
    //status 测试 ------------------------------------------------------   end
}
