<?php
namespace System\Classes;

use PHPUnit\Framework\TestCase;
use Marmot\Core;

class CacheTest extends TestCase
{

    private $stub;
    private $cacheKeyPrefix = 'pcore';
    private $data;

    public function setUp()
    {
        // $this->stub = $this->getMockForAbstractClass('System\Classes\Cache');
        $this->stub = $this->getMockBuilder('System\Classes\Cache')
                  ->setConstructorArgs(array($this->cacheKeyPrefix))
                  ->getMockForAbstractClass();

        //初始化数据
        $this->data = array('key1'=>'value1',
                            'key2'=>'value2',
                            'key3'=>'value3',
                            'key4'=>'value4',
                            'key5'=>'value5');
    }

    public function tearDown()
    {
        unset($this->stub);
        unset($this->data);
        //清空缓存数据
        Core::$cacheDriver->flushAll();
    }

    /**
     * 我们需要测试添加数据,且能正常获取数据.
     * 测试 Cache::save()
     */
    public function testSave()
    {

        foreach ($this->data as $key => $value) {
            $this->assertTrue($this->stub->save($key, $value));
        }
        
        foreach ($this->data as $key => $value) {
            $this->assertEquals(
                Core::$cacheDriver->fetch($this->cacheKeyPrefix.'_'.$key),
                $value,
                'key: '.$key.' not equal value: '.$value
            );
        }
    }

    /**
     * 测试 Cache::get()
     */
    public function testGet()
    {
        
        foreach ($this->data as $key => $value) {
            $this->assertTrue(Core::$cacheDriver->save($this->cacheKeyPrefix.'_'.$key, $value));
        }

        //循环通过get方法检查调取数据是否正确
        //invokeArgs:static method 传递 null
        foreach ($this->data as $key => $value) {
            $this->assertEquals(
                $this->stub->get($key),
                $value,
                'key: '.$key.' not equal value: '.$value
            );
        }
    }

    /**
     * 测试 Cache:del()
     */
    public function testDel()
    {

        foreach ($this->data as $key => $value) {
            $this->assertTrue(Core::$cacheDriver->save($this->cacheKeyPrefix.'_'.$key, $value));
        }

        //循环删除数据,
        foreach ($this->data as $key => $value) {
            // $this->assertTrue($method->invokeArgs(null, array($key)),'key not del');//static method 传递 null
            $this->assertTrue($this->stub->del($key), 'key not del');//static method 传递 null
        }

        //检查数据是否删除成功
        foreach ($this->data as $key => $value) {
            $this->assertEmpty(
                Core::$cacheDriver->fetch($this->cacheKeyPrefix.'_'.$key),
                'key: '.$key.' is not empty'
            );
        }
    }

    /**
     * 测试 Cache:getList()
     */
    public function testGetList()
    {
        
        //循环保存数据
        //因为在cache层,有前缀
        //所以这里需要拼接cacheKeyPrefix
        $keys = array();
        foreach ($this->data as $key => $value) {
            $this->assertTrue(Core::$cacheDriver->save($this->cacheKeyPrefix.'_'.$key, $value), ' save fails');
            $keys[] = $key;
        }

        // list($hits, $misses) = $method->invokeArgs(null, array($keys));
        list($hits, $misses) = $this->stub->getList($keys);
        
        //我们先测试数据批量获取第一次是全部命中
        $this->assertEquals(array_values($this->data), $hits);
        //misses的id列表为空
        $this->assertTrue(is_array($misses));
        $this->assertCount(0, $misses);

        //我们删除一个key,再次测试hits数据和misses数据.我们删除第一个key和第二个key
        $delKey[] = $keys[0];
        $this->assertTrue(
            Core::$cacheDriver->delete($this->cacheKeyPrefix.'_'.$keys[0]),
            ' delete key: '.$key[0].' fails'
        );
        $delKey[] = $keys[1];
        $this->assertTrue(
            Core::$cacheDriver->delete($this->cacheKeyPrefix.'_'.$keys[1]),
            ' delete key: '.$key[1].' fails'
        );
        //我们弹出元素2次
        array_shift($this->data);
        array_shift($this->data);

        //再次批量获取
        list($hits, $misses) = $this->stub->getList($keys);

        //测试命中数据和弹出数据一致
          $this->assertEquals(array_values($this->data), $hits, ' hit not same');

          //测试misses数据为delKey
          $this->assertEquals($delKey, $misses, ' misses not same');
    }
}
