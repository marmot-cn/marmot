<?php
/**
 * User/Repository/UserRepository.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */
class UserRepositoryTest extends GenericTestsDatabaseTestCase{

	public $fixtures = array('pcore_user');

	private $stub;

	public function setUp(){
		$this->stub = Core::$_container->get('User\Repository\UserRepository');

		parent::setUp();
	}

	/**
	 * 测试用户仓库构建
	 */
	public function testUserRepositoryConstructor(){

		//测试RowCacheQuery构造成功
		$userRowCacheQuery = $this->getPrivateProperty('User\Repository\UserRepository','userRowCacheQuery');
		$this->assertInstanceof('User\Repository\Query\UserRowCacheQuery',$userRowCacheQuery->getValue($this->stub));
	}

	/**
	 * 测试用户仓库获取单独数据
	 * @todo 需要测试头像图片路径
	 */
	public function testUserRepositoryGetOne(){
		
		//测试用户id
		$testUserId = 1;	

		//期待数组
		$expectedArray = Core::$_dbDriver->query('SELECT avatarId,cellPhone,realName,provinceId,cityId,districtId,email,qq FROM pcore_user WHERE id='.$testUserId);
		$expectedArray = $expectedArray[0];

		$resultArray = $this->stub->getOne($testUserId);

		//期待返回相同
		$this->assertEquals($expectedArray['cellPhone'],$resultArray['cellPhone']);
		$this->assertEquals($expectedArray['realName'],$resultArray['realName']);
		$this->assertEquals($expectedArray['provinceId'],$resultArray['provinceId']);
		$this->assertEquals($expectedArray['cityId'],$resultArray['cityId']);
		$this->assertEquals($expectedArray['districtId'],$resultArray['districtId']);
		$this->assertEquals($expectedArray['email'],$resultArray['email']);
		$this->assertEquals($expectedArray['qq'],$resultArray['qq']);
		//期待图片地址返回相同
		$file = Core::$_container->get('Common\Model\File');
		$expectedFilePath = $file->getFileData($expectedArray['avatarId']);
		$expectedFilePath = $file->getAttachDir().$expectedFilePath['filePath'];

		$this->assertEquals($expectedFilePath,$resultArray['avatar']);
	}
}

