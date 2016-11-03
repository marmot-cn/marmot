<?php
namespace Member\Controller;

use tests\GenericTestsDatabaseTestCase;

class UserControllerTest extends GenericTestsDatabaseTestCase
{
   
    private $stub;

    public function setUp()
    {
        $this->stub = new UserController();
    }

    public function tearDown()
    {
        unset($this->stub);
    }

    public function testCorrectExtendsController()
    {
        $this->assertInstanceof('System\Classes\Controller', $this->stub);
    }

    //测试其他函数
}
