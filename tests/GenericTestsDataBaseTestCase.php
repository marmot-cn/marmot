<?php
namespace tests;

/**
 * 框架自用数据库测试抽象用例,其他数据库测试继承该类即可.
 * 在不同的数据库测试用例中重用,降低数据库开销.
 * 数据库的表,用户名这些都配置在xml文件中,可以根据自己的环境需要去修改 phpunit.xml
 * 所有数据库表的测试都需要继承该类,用于测试数据库环境.
 * 配合镜像可以在本机独立的测试单元测试和集成测试.
 * @author chloroplast
 * @version 1.0.20160218
 */

abstract class GenericTestsDatabaseTestCase extends PHPUnit_Extensions_Database_TestCase
{

    // 只实例化 pdo 一次，供测试的清理和装载基境使用
    static private $pdo = null;

    // 对于每个测试，只实例化 PHPUnit_Extensions_Database_DB_IDatabaseConnection 一次
    private $conn = null;

    //我们需要测试表对应的xml文件,我们可以通过mysql命令导出这些xml文件
    //mysqldump --xml -t -u [username] --password=[password] [database] [table]> /path/to/file.xml
    protected $fixtures = array();

    final public function getConnection()
    {

        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO(
                    $GLOBALS['DB_DSN'],
                    $GLOBALS['DB_USER'],
                    $GLOBALS['DB_PASSWD'],
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    /**
     * 建立数据集从设置的fixtures读取需要建立的表的数据集,然后用于环境测试.
     */
    public function getDataSet($fixtures = array())
    {
        if (empty($fixtures)) {
            $fixtures = $this->fixtures;
        }
        $compositeDs = new PHPUnit_Extensions_Database_DataSet_CompositeDataSet(array());

        $fixturePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Fixtures';

        //循环载入基境的表
        foreach ($fixtures as $fixture) {
            $path =  $fixturePath . DIRECTORY_SEPARATOR . $fixture.'.xml';
            $ds = $this->createMySQLXMLDataSet($path);
            $compositeDs->addDataSet($ds);
        }
        return $compositeDs;
    }

    public function loadDataSet($dataSet)
    {
        // set the new dataset
        $this->getDatabaseTester()->setDataSet($dataSet);
        // call setUp whateverhich adds the rows
        $this->getDatabaseTester()->onSetUp();
    }

    public function tearDown()
    {

        $allTables = $this->getDataSet($this->fixtures)->getTableNames();
        foreach ($allTables as $table) {
            // drop table
            $conn = $this->getConnection();
            $pdo = $conn->getConnection();
            $pdo->exec('TRUNCATE TABLE '.$table.';');
        }
        parent::tearDown();
    }

    
    /**
     * getPrivateMethod
     *
     * @author  Joe Sexton <joe@webtipblog.com>
     * @param   string $className
     * @param   string $methodName
     * @return  ReflectionMethod
     */
    public function getPrivateMethod($className, $methodName)
    {
        $reflector = new ReflectionClass($className);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);
 
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
    public function getPrivateProperty($className, $propertyName)
    {
        $reflector = new ReflectionClass($className);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);
 
        return $property;
    }
}
