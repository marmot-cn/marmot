<?php
namespace Member\Translator;

use tests\GenericTestsDatabaseTestCase;
use Marmot\Core;
use Member\Utils\UserUtils;

/**
 * Member\Translator\UserTranslator.class.php 测试文件
 * @author chloroplast
 * @version 1.0.0:2016.04.18
 */
class UserTranslatorTest extends GenericTestsDatabaseTestCase
{

    use UserUtils;

    private $stub;

    public $fixtures = array('pcore_user');

    public function setUp()
    {
        $this->stub = new UserTranslator();
        parent::setUp();
    }

    /**
     * 测试翻译数组转换对象
     */
    public function testArrayToObject()
    {

        $expectedArray = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE 1 LIMIT 1'
        );
        $expectedArray = $expectedArray[0];
        $user = $this->stub->arrayToObject($expectedArray);
        $this->assertInstanceof('Member\Model\User', $user);
        $this->compareArrayAndObject($expectedArray, $user);
    }

    /**
     * 测试翻译对象转换为数组
     */
    public function testObjectToArray()
    {

        $dbData = Core::$dbDriver->query(
            'SELECT * FROM pcore_user WHERE 1 LIMIT 1'
        );
        $dbData = $dbData[0];

        $user = $this->stub->arrayToObject($dbData);
        $expectedArray = $this->stub->objectToArray($user);
        $this->assertInternalType('array', $expectedArray);

       
        $this->compareArrayAndObject($expectedArray, $user);
    }
}
