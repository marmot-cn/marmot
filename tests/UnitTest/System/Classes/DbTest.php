<?php
namespace System\Classes;

/**
 * 测试Db封装类,需要测试如下:
 * 1. delete
 * 2. insert
 * 3. update
 * 4. select
 */
class DbTest extends GenericTestsDatabaseTestCase
{

    public $fixtures = array('pcore_system_test_a','pcore_system_test_b');

    private $table = 'system_test_a';

    private $stub;

    public function setUp()
    {
        //mock仿件对象
        // $this->stub = $this->getMockForAbstractClass('System\Classes\Db');
        $this->stub = $this->getMockBuilder('System\Classes\Db')
              ->setConstructorArgs(array($this->table))
              ->getMockForAbstractClass();

        // $this->stub = new System\Classes\Db();
        parent::setUp();
    }

    /**
     * 测试 Db::delete()删除方法
     */
    public function testDbDelete()
    {

        //删除两条语句

        // $row = $method->invokeArgs(null, array(array('user'=>'userA2')));
        $row = $this->stub->delete(array('user'=>'userA2'));
        //确认返回影响行数为2
        $this->assertEquals(2, $row);

        //检索出所有row的总数
        $results = Core::$_dbDriver->query('SELECT COUNT(*) as count FROM pcore_system_test_a');
        //确认总行数已经减少
        $this->assertEquals(1, $results[0]['count'], 'after delete the left data size of date not right');

        //根据检索条件搜索确认数据已经删除成功
        $results = Core::$_dbDriver->query('SELECT COUNT(*) as count FROM pcore_system_test_a WHERE user=\'userA2\'');
        $this->assertEquals(0, $results[0]['count'], 'the condition data size not equal to 0');
    }

    /**
     * 测试 Db::insert()添加方法,返回lastrInserId
     */
    public function testDbInsertWithLastInserId()
    {

        //插入数据
        $row = $this->stub->insert(array('title'=>'titleA3','user'=>'userA3'));
        // $row = $method->invokeArgs(null, array(array('title'=>'titleA3','user'=>'userA3')));

        //确认插入一条数据返回一行(row)影响
        $this->assertEquals(4, $row);

        //检索出最新的插入数据,看数据是否插入成功
        $results = Core::$_dbDriver->query('SELECT * FROM pcore_system_test_a');
        //在插入一条数据后,tableA有3条数据,我们验证是否符合数据条目符合
        $this->assertEquals(4, sizeof($results), 'after insert table_a count not right');

        //验证我们最新插入的数据
        $this->assertEquals(4, $results[3]['id']);
        $this->assertEquals('titleA3', $results[3]['title']);
        $this->assertEquals('userA3', $results[3]['user']);
    }

    /**
     * 测试 Db::insert()添加方法,返回影响的行数
     */
    public function testDbInsertWithAffectRows()
    {

        //插入数据
        // $row = $method->invokeArgs(null, array(array('title'=>'titleA3','user'=>'userA3'),false));
        $row = $this->stub->insert(array('title'=>'titleA3','user'=>'userA3'), false);

        //确认插入一条数据返回一行(row)影响
        $this->assertEquals(1, $row);

        //检索出最新的插入数据,看数据是否插入成功
        $results = Core::$_dbDriver->query('SELECT * FROM pcore_system_test_a');

        //在插入一条数据后,tableA有3条数据,我们验证是否符合数据条目符合
        $this->assertEquals(4, sizeof($results), 'after insert table_a count not right');

        //验证我们最新插入的数据
        $this->assertEquals(4, $results[3]['id']);
        $this->assertEquals('titleA3', $results[3]['title']);
        $this->assertEquals('userA3', $results[3]['user']);
    }

    /**
     * 测试 Db::select()添加方法,返回查询到的结果
     */
    public function testDbSelect()
    {

        //通过MyPdo类检索数据
        // $results = $method->invokeArgs(null, array('SELECT * FROM pcore_system_test_a'));
        $results = $this->stub->select('');

        //tableA有3条数据,我们验证是否符合数据条目符合
        $this->assertEquals(3, sizeof($results), 'table_a count not right');

        //验证第一条数据记录
        $this->assertEquals(1, $results[0]['id']);
        $this->assertEquals('titleA1', $results[0]['title']);
        $this->assertEquals('userA1', $results[0]['user']);

        //验证第二条数据记录
        $this->assertEquals(2, $results[1]['id']);
        $this->assertEquals('titleA2', $results[1]['title']);
        $this->assertEquals('userA2', $results[1]['user']);

        //验证第三条数据记录
        $this->assertEquals(3, $results[2]['id']);
        $this->assertEquals('titleA2', $results[2]['title']);
        $this->assertEquals('userA2', $results[2]['user']);
    }
    /**
     * 测试 Db::update()添加方法,返回影响的行数
     */
    public function testDbUpdate()
    {

        //更新多条数据
        // $row = $method->invokeArgs(null, array(array('title'=>'titleA0'),array('user'=>'userA2')));
        $row = $this->stub->update(array('title'=>'titleA0'), array('user'=>'userA2'));
        //确认返回值等于更新的条数
        $this->assertEquals(2, $row);

        //检索数据
        $results = Core::$_dbDriver->query('SELECT * FROM pcore_system_test_a');

        //验证数据总数没有发生变化
        $this->assertEquals(3, sizeof($results), 'after update table_a count not right');

        //确认更新数据
        //索引从0,1 对应id 为 1,2
        $this->assertEquals(2, $results[1]['id']);
        $this->assertEquals('titleA0', $results[1]['title']);
        $this->assertEquals('userA2', $results[1]['user']);

        $this->assertEquals(3, $results[2]['id']);
        $this->assertEquals('titleA0', $results[2]['title']);
        $this->assertEquals('userA2', $results[2]['user']);
    }
}
