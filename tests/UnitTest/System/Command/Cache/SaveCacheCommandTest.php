<?php
namespace System\Command\Cache;

use Marmot\Core;

/**
 * 测试添加缓存命令(AddCacheCommand),测试如下功能:
 * 1. 测试execute,是否保存成功
 * 2. 测试undo,是否删除
 * @author chloroplast
 * @version 1.0.20160217
 */
class SaveCacheCommandTest extends \PHPUnit_Framework_TestCase
{
    
    private $command;
    private $key;
    private $value;

    public function setUp()
    {
        $this->key = 'key';
        $this->value = 'value';
        $this->command = new \System\Command\Cache\SaveCacheCommand($this->key, $this->value);
    }

    public function tearDown()
    {
        //清空缓存数据
        Core::$cacheDriver->flushAll();
        unset($this->command);
    }
    /**
     * 测试command::execute()
     */
    public function testExecute()
    {

        //测试命令是否是SaveCacheCommand
        $this->assertTrue($this->command instanceof \System\Command\Cache\SaveCacheCommand);
        //执行保存数据命令
        $returnStatus = $this->command->execute();
        //测试是否返回true
        $this->assertTrue($returnStatus);
        //测试是否key存在
        $this->assertTrue(Core::$cacheDriver->contains($this->key));
        //测试是否保存成功,根据key是否可以得到value
        $this->assertEquals(Core::$cacheDriver->fetch($this->key), $this->value);
    }

    /**
     * 测试command::undo()
     */
    public function testUndo()
    {
        //执行回滚命令
        $this->command->undo();
        //测试是否key存在
        $this->assertFalse(Core::$cacheDriver->contains($this->key));
        //测试是否回滚成功,根据key获取值为空
        $this->assertEmpty(Core::$cacheDriver->fetch($this->key));
    }
}
