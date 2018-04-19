<?php
namespace Common\Model;

use Marmot\Core;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    private $stub;

    public function setUp()
    {
        $this->stub = $this->getMockBuilder('Common\Model\Document')
                      ->getMockForAbstractClass();
    }

    public function tearDown()
    {
        unset($this->stub);
    }

    /**
     * Document 领域对象,测试构造函数
     */
    public function testDocumentConstructor()
    {
        $this->assertEquals('', $this->stub->getId());
        $this->assertEquals(array(), $this->stub->getData());
    }

    public function testSetId()
    {
        $this->stub->setId(1);
        $this->assertEquals(1, $this->stub->getId());
    }
    
    //data 测试 --------------------------------------------------- start
    /**
     * 设置 Document setData() 正确的传参类型,期望传值正确
     */
    public function testSetDataCorrectType()
    {
        $this->stub->setData(array(1,2));
        $this->assertEquals(array(1,2), $this->stub->getData());
    }

     /**
     * 设置 Document setData() 错误的传参类型,期望期望抛出TypeError exception
     *
     * @expectedException TypeError
     */
    public function testSetDataWrongType()
    {
        $this->stub->SetData(1);
    }
   
    //data 测试 ---------------------------------------------------   end
}
