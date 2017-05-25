<?php
namespace Member\Model;

use Marmot\Core;
use tests\GenericTestsDatabaseTestCase;

use Member\Utils\ObjectGenerate;
use Member\Utils\UserUtils;

class UserMtehodsTest extends GenericTestsDatabaseTestCase
{

    use UserUtils;

    public $fixtures = array(
        'pcore_user',
    );

    public function setUp()
    {
        //这里不构建初始数据,只是在最后清理数据
        $this->stub = new User();
    }

    public function tearDown()
    {
        Core::$cacheDriver->flushAll();
        parent::tearDown();
    }

    public function testSignUp()
    {
        $user = ObjectGenerate::generateUser();
        $result = $user->signUp();
        $this->assertTrue($result);

        //查询数据库,确认数据插入成功
        $expectedArray = Core::$dbDriver->query('SELECT * FROM pcore_user WHERE user_id='.$user->getId());
        $expectedArray = $expectedArray[0];

        $this->compareArrayAndObject($expectedArray, $user);
    }

    public function testSignUpDuplicateCellphone()
    {
        $user = ObjectGenerate::generateUser(0, 0);
        $result = $user->signUp();
        $this->assertTrue($result);

        $user = ObjectGenerate::generateUser(
            1,
            1,
            array('cellPhone'=>$user->getCellphone())
        );
        $result = $user->signUp();
        $this->assertFalse($result);
        $this->assertEquals(USER_IDENTIFY_DUPLICATE, Core::getLastError()->getId());
    }

    public function testUpdatePassword()
    {
        $user = ObjectGenerate::generateUser();
        $user->signUp();
        $oldEncryptedPassword = $user->getPassword();
        $oldSalt = $user->getSalt();

        $faker = \Faker\Factory::create('zh_CN');
        $user->encryptPassword($faker->password);
        $this->assertNotEquals($oldSalt, $user->getSalt());
        $this->assertNotEquals($oldEncryptedPassword, $user->getPassword());

        $result = $user->updatePassword('111111');
        $this->assertTrue($result);

        //查询数据库,确认数据插入成功
        $expectedArray = Core::$dbDriver->query('SELECT * FROM pcore_user WHERE user_id='.$user->getId());
        $expectedArray = $expectedArray[0];

        $this->compareArrayAndObject($expectedArray, $user);
    }
}
