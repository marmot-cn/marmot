<?php
namespace System\Command\Cache;

/**
 * 测试添加缓存命令(DelCacheCommand),测试如下功能:
 * 1. 测试execute,是否删除成功
 * @author chloroplast
 * @version 1.0.20160217
 */
class DelCacheCommandTest extends PHPUnit_Framework_TestCase
{
    
    private $command;
    private $key;
    private $value;

    public function setUp()
    {
        $this->key = 'key';
        $this->value = 'value';
        $this->command = new System\Command\Cache\DelCacheCommand($this->key, $this->value);
    }

    public function tearDown()
    {
        //清空缓存数据
        Core::$_cacheDriver->flushAll();
        unset($this->command);
    }
    /**
     * 测试command::execute()
     */
    public function testExecute()
    {

        //测试命令是否是AddCacheCommand
        $this->assertTrue($this->command instanceof System\Command\Cache\DelCacheCommand);
        //执行保存数据命令
        $returnStatus = $this->command->execute();
        //测试是否返回true
        $this->assertTrue($returnStatus);
        //测试期望key不存在
        $this->assertFalse(Core::$_cacheDriver->contains($this->key), 'DelCacheCommand undo not clear value');
        //测试期望内容不存在
        $this->assertEmpty(Core::$_cacheDriver->fetch($this->key), 'DelCacheCommand undo not clear value');
    }
}
