<?php
/**
 * User\Model\Userclass.php 测试文件
 * @author chloroplast
 * @version 1.0.0:2016.04.15
 */

class UserTest {

	private $stub

	public function setUp(){
		$this->stub = new User\Model\User();
	}

	/**
	 * User 用户领域对象,测试构造函数
	 */
	public function testUserConstructor(){
		//测试初始化用户id
		$idParameter = $this->getPrivateProperty(User\Model\User,'id');
		$this->assertEquals(0,$idParameter->getValue($this->stub));

		//测试初始化用户名字
		$nameParameter = $this->getPrivateProperty(User\Model\User,'name');
		$this->assertEmpty($nameParameter->getValue($this->stub));

		//测试初始化用户手机号
		$cellPhoneParameter = $this->getPrivateProperty(User\Model\User,'cellPhone');
		$this->assertEmpty($cellPhoneParameter->getValue($this->stub));

		//测试初始化用户qq
		$qqParameter = $this->getPrivateProperty(User\Model\User,'qq');
		$this->assertEmpty($qqParameter->getValue($this->stub));

		//测试初始化用户邮箱
		$emailParameter = $this->getPrivateProperty(User\Model\User,'email');
		$this->assertEmpty($emailParameter->getValue($this->stub));

		//测试初始化用户注册时间
		$createTimeParameter = $this->getPrivateProperty(User\Model\User,'createTime');
		$this->assertGreaterThan(0,$createTimeParameter->getValue($this->stub));

		//测试初始化用户状态
		$statusParameter = $this->getPrivateProperty(User\Model\User,'status');
		$this->assertEquals(USER_STATUS_NORMAL,$statusParameter->getValue($this->stub));

		//测试初始化用户住址区
		$districtParameter = $this->getPrivateProperty(User\Model\User,'district');
		$this->assertInstanceof(,$districtParameter->getValue($this->stub));

	}


	//id 测试 ---------------------------------------------------------- start
	/**
	 * 设置 User setId() 正确的传参类型,期望传值正确
	 */
	public function testSetIdCorrectType(){
		$this->stub->setId(1);
		$this->assertEquals(1,$this->stub->getId());
	}

	/**
	 * 设置 User setId() 错误的传参类型,期望期望抛出TypeError exception
	 *
	 * @expectedException TypeError 
	 */
	public function testSetIdWrongType(){
		$this->stub->setId('string');
	}

	/**
	 * 设置 User setId() 错误的传参类型.但是传参是数值,期望返回类型正确,值正确.
	 */
	public function testSetIdWrongTypeButNumeric(){
		$this->stub->setId('1');
		$this->assertTrue(is_int($this->stub->getId()));
		$this->assertEquals(1,$this->stub->getId());
	}
	//id 测试 ----------------------------------------------------------   end

	//name 测试 -------------------------------------------------------- start
	/**
	 * 设置 User setName() 正确的传参类型,期望传值正确
	 */
	public function testSetNameCorrectType(){
		$this->stub->setName('41893204');
		$this->assertEquals('41893204',$this->stub->getName());
	}

	/**
	 * 设置 User setName() 错误的传参类型,期望期望抛出TypeError exception
	 *
	 * @expectedException TypeError 
	 */
	public function testSetNameWrongType(){
		$this->stub->setName(array(1,2,3));
	}

	/**
	 * 设置 User setName() 正确的传参类型,但是不属于QQ格式,期望返回空.
	 */
	public function testSetNameCorrectTypeButNotEmail(){
		$this->stub->setName('string');
		$this->assertEquals('',$this->stub->getName());
	}
	//name 测试 --------------------------------------------------------   end

	//cellPhone 测试 --------------------------------------------------- start
	/**
	 * 设置 User setCellPhone() 正确的传参类型,期望传值正确
	 */
	public function testSetCellPhoneCorrectType(){
		$this->stub->setCellPhone('15202939435');
		$this->assertEquals('15202939435',$this->stub->getCellPhone());
	}

	/**
	 * 设置 User setCellPhone() 错误的传参类型,期望期望抛出TypeError exception
	 *
	 * @expectedException TypeError 
	 */
	public function testSetCellPhoneWrongType(){
		$this->stub->setCellPhone(array(1,2,3));
	}

	/**
	 * 设置 User setCellPhone() 正确的传参类型,但是不属于手机格式,期望返回空.
	 */
	public function testSetCellPhoneCorrectTypeButNotEmail(){
		$this->stub->setCellPhone('15202939435'a);
		$this->assertEquals('',$this->stub->getCellPhone());
	}
	//cellPhone 测试 ---------------------------------------------------   end

	//qq 测试 ---------------------------------------------------------- start
	/**
	 * 设置 User setQq() 正确的传参类型,期望传值正确
	 */
	public function testSetQqCorrectType(){
		$this->stub->setQq('41893204');
		$this->assertEquals('41893204',$this->stub->getQq());
	}

	/**
	 * 设置 User setQq() 错误的传参类型,期望期望抛出TypeError exception
	 *
	 * @expectedException TypeError 
	 */
	public function testSetQqWrongType(){
		$this->stub->setQq(array(1,2,3));
	}

	/**
	 * 设置 User setQq() 正确的传参类型,但是不属于QQ格式,期望返回空.
	 */
	public function testSetQqCorrectTypeButNotEmail(){
		$this->stub->setQq('string');
		$this->assertEquals('',$this->stub->getQq());
	}
	//qq 测试 ----------------------------------------------------------   end

	//email 测试 ------------------------------------------------------- start
	/**
	 * 设置 User setEmail() 正确的传参类型,期望传值正确
	 */
	public function testSetEmailCorrectType(){
		$this->stub->setEmail('41893204@qq.com');
		$this->assertEquals('41893204@qq.com',$this->stub->getEmail());
	}

	/**
	 * 设置 User setEmail() 错误的传参类型,期望期望抛出TypeError exception
	 *
	 * @expectedException TypeError 
	 */
	public function testSetEmailWrongType(){
		$this->stub->setEmail(1);
	}

	/**
	 * 设置 User setEmail() 错误的传参类型.但是传参是数值,期望返回类型正确,值正确.
	 */
	public function testSetEmailCorrectTypeButNotEmail(){
		$this->stub->setEmail('string');
		$this->assertEquals('',$this->stub->getEmail());
	}
	//email 测试 -------------------------------------------------------   end

	//createTime 测试 -------------------------------------------------- start
	/**
	 * 设置 User setCreateTime() 正确的传参类型,期望传值正确
	 */
	public function testSetCreateTimeCorrectType(){
		$this->stub->setCreateTime(1460713993);
		$this->assertEquals(1460713993,$this->stub->getCreateTime());
	}

	/**
	 * 设置 User setCreateTime() 错误的传参类型,期望期望抛出TypeError exception
	 *
	 * @expectedException TypeError 
	 */
	public function testSetCreateTimeWrongType(){
		$this->stub->setCreateTime('string');
	}

	/**
	 * 设置 User setCreateTime() 错误的传参类型.但是传参是数值,期望返回类型正确,值正确.
	 */
	public function testSetCreateTimeWrongTypeButNumeric(){
		$this->stub->setCreateTime('1460713993');
		$this->assertTrue(is_int($this->stub->getCreateTime()));
		$this->assertEquals(1460713993,$this->stub->getCreateTime());
	}
	//createTime 测试 --------------------------------------------------   end

	//status 测试 ------------------------------------------------------ start
	/**
	 * 循环测试 User setStatus() 是否符合预定范围
	 *
	 * @dataProvider statusProvider
	 */
	public function testSetStatus($actual,$expected){
		$this->stub->setStatus($actual);
		$this->assertEquals($expected,$this->stub->getStatus());
	}

	/**
	 * 循环测试 User setStatus() 数据构建器
	 */
	public functionstatusProvider(){
		return array(
			array(USER_STATUS_NORMAL,USER_STATUS_NORMAL),
			array(USER_STATUS_BANNED,USER_STATUS_BANNED),
			array(9999,USER_STATUS_NORMAL),
		);
	}

	/**
	 * 设置 User setStatus() 错误的传参类型,期望期望抛出TypeError exception
	 *
	 * @expectedException TypeError 
	 */
	public function testSetStatusWrongType(){
		$this->stub->setStatus('string');
	}
	//status 测试 ------------------------------------------------------   end

	//district 测试 ---------------------------------------------------- start
	/**
	 * 设置 User setDistrict() 正确的传参类型,期望传值正确
	 */
	public function testSetDistrictCorrectType(){
		$object = new Area\Model\Area();		//根据需求自己添加对象的设置,如果需要
		$this->stub->setDistrict($object);
		$this->assertSame($object,$this->stub->getDistrict());
	}

	/**
	 * 设置 User setDistrict() 错误的传参类型,期望期望抛出TypeError exception
	 *
	 * @expectedException TypeError 
	 */
	public function testSetDistrictWrongType(){
		$this->stub->setDistrict($this->testSring);
	}
	//district 测试 ----------------------------------------------------   end
}