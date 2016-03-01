<?php
/**
 * User\Repository\Query\UserRowCacheQuery.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */
class UserRowCacheQueryTest extends GenericTestCase{

	private $stub;
	private $tablepre = 'pcore_';

	public function setUp(){
		$this->stub = Core::$_container->get('User\Repository\Query\UserRowCacheQuery');
	}

	/**
	 * 测试该文件是否正确的继承RowCacheQuery类
	 */
	public function testUserRowCacheQueryCorrectInstanceExtendsRowCacheQuery(){
		$this->assertInstanceof('System\Query\RowCacheQuery',$this->stub);
	}

	/**
	 * 测试该文件是否正确的初始化primaryKey,并且数据库存在该字段
	 */
	public function testUserRowCacheQueryCorrectPrimaryKey(){
		$key = $this->getPrivateProperty('User\Repository\Query\UserRowCacheQuery','primaryKey');

		//判断primaryKey赋值设想一致
		$this->assertEquals('id',$key->getValue($this->stub));
		//检查表是否有该字段
		$results = Core::$_dbDriver->query('SHOW COLUMNS FROM `'.$this->tablepre.'user` LIKE \''.$key->getValue($this->stub).'\'');
		$this->assertNotEmpty($results);//期望检索出表名
	}

	/**
	 * 测试是否cache层赋值正确
	 */
	public function testUserRowCacheQueryCorrectCacheLayer(){
		$cacheLayer = $this->getPrivateProperty('User\Repository\Query\UserRowCacheQuery','cacheLayer');

		$this->assertInstanceof('User\Persistence\UserCache',$cacheLayer->getValue($this->stub));
	}

	/**
	 * 测试是否db层赋值正确
	 */
	public function testAdmissionCacheQueryCorrectDbLayer(){
		$dbLayer = $this->getPrivateProperty('User\Repository\Query\UserRowCacheQuery','dbLayer');

		$this->assertInstanceof('User\Persistence\UserDb',$dbLayer->getValue($this->stub));
	}

}