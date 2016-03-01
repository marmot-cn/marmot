<?php
/**
 * User/Command/UserCommandFactoryTest.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */
class UserCommandFactoryTest extends GenericTestCase{

	private $stub;

	public function setUp(){
		//初始化工厂桩件
		$this->stub = new User\Command\UserCommandFactory();
	}

	/**
	 * 测试SignUp命令返回
	 */
	public function testUserCommandFactorySignUp(){

		$command = $this->stub->createCommand('signUp',new User\Model\User());
		//测试返回类型是否正确
		$this->assertInstanceOf('User\Command\SignUpUserCommand',$command);
		$this->assertInstanceOf('System\Interfaces\Pcommand',$command);
	}

	/**
	 * 测试updatePassword命令返回
	 */
	public function testUserCommandFactoryUpdatePassword(){

		$command = $this->stub->createCommand('updatePassword',new User\Model\User());
		//测试返回类型是否正确
		$this->assertInstanceOf('User\Command\UpdatePasswordUserCommand',$command);
		$this->assertInstanceOf('System\Interfaces\Pcommand',$command);
	}

	/**
	 * 测试updateProfile命令返回
	 */
	public function testUserCommandFactoryUpdateProfile(){

		$command = $this->stub->createCommand('updateProfile',new User\Model\User());
		//测试返回类型是否正确
		$this->assertInstanceOf('User\Command\UpdateProfileUserCommand',$command);
		$this->assertInstanceOf('System\Interfaces\Pcommand',$command);
	}	

}	
?>