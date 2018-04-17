<?php
namespace Member\Translator;

use PHPUnit\Framework\TestCase;

use Marmot\Core;
use Member\Utils\UserUtils;
use Member\Model\User;
use Member\Utils\ObjectGenerate;

/**
 * Member\Translator\UserDataBaseTranslator.class.php 测试文件
 * @author chloroplast
 * @version 1.0.0:2016.04.18
 */
class UserDataBaseTranslatorTest extends TestCase
{
    use UserUtils;

    private $translator;

    public function setUp()
    {
        $this->translator = new UserDataBaseTranslator();
        parent::setUp();
    }

    /**
     * 测试翻译数组转换对象
     */
    public function testArrayToObject()
    {
        $user = ObjectGenerate::generateUser(1);

        $expression = array();
        $expression['user_id'] = $user->getId();
        $expression['cellphone'] = $user->getCellphone();
        $expression['password'] = $user->getPassword();
        $expression['salt'] = $user->getSalt();
        $expression['nick_name'] = $user->getNickName();
        $expression['user_name'] = $user->getUserName();
        $expression['real_name'] = $user->getRealName();
        $expression['create_time'] = $user->getCreateTime();
        $expression['update_time'] = $user->getUpdateTime();
        $expression['status'] = $user->getStatus();
        $expression['status_time'] = $user->getStatusTime();

        $user = $this->translator->arrayToObject($expression);
        $this->assertInstanceof('Member\Model\User', $user);
        $this->compareArrayAndObject($expression, $user);
    }

    /**
     * 测试翻译对象转换为数组
     */
    public function testObjectToArrayCorrectObject()
    {
        $user = ObjectGenerate::generateUser(1);

        $expression = $this->translator->objectToArray($user);

        $this->compareArrayAndObject($expression, $user);
    }

    public function testObjectToArrayIncorrectObject()
    {
        $result = $this->translator->objectToArray(null);
        $this->assertFalse($result);
    }
}
