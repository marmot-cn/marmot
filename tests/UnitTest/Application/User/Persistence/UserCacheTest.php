<?php
/**
 * User/Persistence/UserCache.class.php 测试文件
 * @author chloroplast
 * @version 1.0.20160218
 */
class UserCacheTest extends GenericTestCase{

	private $stub;
	private $tablepre = 'pcore_';

	public function setUp(){
		$this->stub = new User\Persistence\UserCache();
	}

	/**
	 * 测试该文件是否正确的继承cache类
	 */
	public function testUserCacheCorrectInstanceExtendsCache(){
		$this->assertInstanceof('System\Classes\Cache',$this->stub);
	}

	/**
	 * 测试该文件是否正确的初始化key,且和表名一致
	 */
	public function testUserCacheCorrectKey(){
		$key = $this->getPrivateProperty('User\Persistence\UserCache','key');

		//判断key赋值设想一致
		$this->assertEquals('user',$key->getValue($this->stub));
		//检查是否有相同的表名
		//查询出表名
		$results = Core::$_dbDriver->query('SHOW TABLES LIKE \''.$this->tablepre.'user'.'\'');
		$this->assertNotEmpty($results);//期望检索出表名
	}

    /**
     * getPrivateMethod
     *
     * @author  Joe Sexton <joe@webtipblog.com>
     * @param   string $className
     * @param   string $methodName
     * @return  ReflectionMethod
     */
    public function getPrivateMethod( $className, $methodName ) {
        $reflector = new ReflectionClass( $className );
        $method = $reflector->getMethod( $methodName );
        $method->setAccessible( true );
 
        return $method;
    }

    /**
     * getPrivateProperty
     *
     * @author  Joe Sexton <joe@webtipblog.com>
     * @param   string $className
     * @param   string $propertyName
     * @return  ReflectionProperty
     */
    public function getPrivateProperty( $className, $propertyName ) {
        $reflector = new ReflectionClass( $className );
        $property = $reflector->getProperty( $propertyName );
        $property->setAccessible( true );
 
        return $property;
    }

}