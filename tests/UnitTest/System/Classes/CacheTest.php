<?php
namespace System\Classes;

/**
 * 因为cache是一个抽象类,所以我们需要mock一个仿件对象出来用于我们实际的测试.
 * 该抽象类用于对所有cache层的封装,所以我们需要确保其封装的正确性
 * 我们需要测试cached的方法如下:
 * 1. add
 * 2. del
 * 3. get
 * 4. replace
 * 5. getList
 * @author chloroplast
 * @version 1.0.20160218
 */
class CacheTest extends PHPUnit_Framework_TestCase
{

    private $stub;
    private $cacheKeyPrefix = 'pcore';
    private $data;

    public function setUp()
    {

        // //mock仿件对象
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
        Core::$_cacheDriver->flushAll();
    }

    /**
     * 我们需要测试添加数据,且能正常获取数据.
     * 测试 Cache::add()
     */
    public function testAdd()
    {

        foreach ($this->data as $key => $value) {
            $this->assertTrue($this->stub->save($key, $value));
        }
        //循环检查数据已经保存成功,因为在cache层,有前缀
        //所以这里需要拼接cacheKeyPrefix
        foreach ($this->data as $key => $value) {
            $this->assertEquals(
                Core::$_cacheDriver->fetch($this->cacheKeyPrefix.'_'.$key),
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
        
        //循环保存数据
        foreach ($this->data as $key => $value) {
            $this->assertTrue(Core::$_cacheDriver->save($this->cacheKeyPrefix.'_'.$key, $value));
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

        //循环保存数据
        //因为在cache层,有前缀
        //所以这里需要拼接cacheKeyPrefix
        foreach ($this->data as $key => $value) {
            $this->assertTrue(Core::$_cacheDriver->save($this->cacheKeyPrefix.'_'.$key, $value));
        }

        //循环删除数据,
        foreach ($this->data as $key => $value) {
            // $this->assertTrue($method->invokeArgs(null, array($key)),'key not del');//static method 传递 null
            $this->assertTrue($this->stub->del($key), 'key not del');//static method 传递 null
        }

        //检查数据是否删除成功
        foreach ($this->data as $key => $value) {
            $this->assertEmpty(
                Core::$_cacheDriver->fetch($this->cacheKeyPrefix.'_'.$key),
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
        $keys = '';
        foreach ($this->data as $key => $value) {
            $this->assertTrue(Core::$_cacheDriver->save($this->cacheKeyPrefix.'_'.$key, $value), ' save fails');
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
            Core::$_cacheDriver->delete($this->cacheKeyPrefix.'_'.$keys[0]),
            ' delete key: '.$key[0].' fails'
        );
        $delKey[] = $keys[1];
        $this->assertTrue(
            Core::$_cacheDriver->delete($this->cacheKeyPrefix.'_'.$keys[1]),
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
