<?php
namespace System\Classes;

/**
 * 测试框架内封装的MyPdo,我们需要:
 * 1.建立基境(fixture)
 * 2.验证各个方法
 * 3.拆除基境(fixture)
 *
 * 这个类因为pdo类封装的还不够严谨,仅仅测试用于可用即可,
 * 稍后会完善该类补全所有功能的测试.
 * @author chloroplast
 * @version 1.0.20160218
 */
 
class MyPdoTest extends GenericTestsDatabaseTestCase
{

    public $fixtures = array('pcore_system_test_a','pcore_system_test_b');

    public function setUp()
    {
        parent::setUp();
    }
     /**
      * 测试MyPdo的检索功能
      */
    public function testMyPdoQuery()
    {

        //测试传入空的sql语句返回false
        $this->assertFalse(Core::$_dbDriver->query(''));
        
        //通过MyPdo类检索数据
        $results = Core::$_dbDriver->query('SELECT * FROM pcore_system_test_a');

        //tableA有2条数据,我们验证是否符合数据条目符合
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
     * 测试MyPdo的插入功能
     */
    public function testMyPdoInsert()
    {

        //插入数据
        $row = Core::$_dbDriver->insert('pcore_system_test_a', array('title'=>'titleA3','user'=>'userA3'));

        //确认插入一条数据返回一行(row)影响
        $this->assertEquals(1, $row);

        $conn = $this->getConnection()->getConnection();
        //检索出最新的插入数据,看数据是否插入成功
        $query = $conn->query('SELECT * FROM pcore_system_test_a');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        //在插入一条数据后,tableA有3条数据,我们验证是否符合数据条目符合
        $this->assertEquals(4, sizeof($results), 'after insert table_a count not right');

        //验证我们最新插入的数据
        $this->assertEquals(4, $results[3]['id']);
        $this->assertEquals('titleA3', $results[3]['title']);
        $this->assertEquals('userA3', $results[3]['user']);
    }

    /**
     *
     */
    public function testMyPdoLastInsertId()
    {

        //插入数据
        Core::$_dbDriver->insert('pcore_system_test_a', array('title'=>'titleA3','user'=>'userA3'));
        //确认返回数据的id为自增最新的id
        $this->assertEquals(4, Core::$_dbDriver->lastInertId());
    }

    /**
     * 测试MyPdo的更新功能
     */
    public function testMyPdoUpdate()
    {

        //更新多条数据
        $row = Core::$_dbDriver->update('pcore_system_test_a', array('title'=>'titleA0'), array('user'=>'userA2'));
        //确认返回值等于更新的条数
        $this->assertEquals(2, $row);

        //检索数据
        $conn = $this->getConnection()->getConnection();
        $query = $conn->query('SELECT * FROM pcore_system_test_a');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

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

    /**
     * 测试MyPdo的删除功能
     */
    public function testMyPdoDelete()
    {
        //删除两条语句
        $row = Core::$_dbDriver->delete('pcore_system_test_a', array('user'=>'userA2'));

        //确认返回影响行数为2
        $this->assertEquals(2, $row);

        //检索出所有row的总数
        $conn = $this->getConnection()->getConnection();
        $query = $conn->query('SELECT COUNT(*) as count FROM pcore_system_test_a');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        //确认总行数已经减少
        $this->assertEquals(1, $results[0]['count'], 'after delete the left data size of date not right');

        //根据检索条件搜索确认数据已经删除成功
        $query = $conn->query('SELECT COUNT(*) as count FROM pcore_system_test_a WHERE user=\'userA2\'');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $this->assertEquals(0, $results[0]['count'], 'the condition data size not equal to 0');
    }

    /**
     * 测试MyPdo的事务功能commit
     */
    public function testMyPdoTransactionCommit()
    {

        //查出旧数据
        $conn = $this->getConnection()->getConnection();
        $query = $conn->query('SELECT * FROM pcore_system_test_a');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $oldCount = sizeof($results);

        //开启事务
        Core::$_dbDriver->beginTA();
        //插入一条语句
        Core::$_dbDriver->insert('pcore_system_test_a', array('title'=>'titleA3','user'=>'userA3'));
        //插入一条语句
        Core::$_dbDriver->insert('pcore_system_test_a', array('title'=>'titleA4','user'=>'userA4'));
        //commit提交
        $status = Core::$_dbDriver->commit();
        $this->assertTrue($status);
        //检索插入的数据已经插入成功
        //检索总数据数量为旧的总数+2
        $query = $conn->query('SELECT * FROM pcore_system_test_a');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $newCount = sizeof($results);

        $this->assertEquals($oldCount+2, $newCount);

        //检索插入数据的内容正确
        //索引从0,1 对应id 为 1,2
        $this->assertEquals(4, $results[3]['id']);
        $this->assertEquals('titleA3', $results[3]['title']);
        $this->assertEquals('userA3', $results[3]['user']);

        $this->assertEquals(5, $results[4]['id']);
        $this->assertEquals('titleA4', $results[4]['title']);
        $this->assertEquals('userA4', $results[4]['user']);
    }

    /**
     * 测试MyPdo的事务功能回滚
     */
    public function testMyPdoTransactionRollBack()
    {
        //查出旧数据
        $conn = $this->getConnection()->getConnection();
        $query = $conn->query('SELECT * FROM pcore_system_test_a');
        $oldResults = $query->fetchAll(PDO::FETCH_ASSOC);

        //开启事务
        Core::$_dbDriver->beginTA();
        //插入一条语句
        Core::$_dbDriver->insert('pcore_system_test_a', array('title'=>'titleA3','user'=>'userA3'));
        //插入一条语句
        Core::$_dbDriver->insert('pcore_system_test_a', array('title'=>'titleA4','user'=>'userA4'));
        //回滚
        $status = Core::$_dbDriver->rollBack();
        $this->assertTrue($status);
        //检索插入的数据没有插入成功
        $query = $conn->query('SELECT * FROM pcore_system_test_a');
        $newResults = $query->fetchAll(PDO::FETCH_ASSOC);

        //确认旧数据的内容一致
        $this->assertEquals($oldResults, $newResults);
    }

    public function testMyPdoTransactionRollBackWhenSqlFail()
    {
    
        //查出旧数据
        $conn = $this->getConnection()->getConnection();
        $query = $conn->query('SELECT * FROM pcore_system_test_a');
     
        $oldResults = $query->fetchAll(PDO::FETCH_ASSOC);
        //开启事务
        Core::$_dbDriver->beginTA();
        //插入一条语句
        $result = Core::$_dbDriver->insert('pcore_system_test_a', array('title'=>'titleA3','user'=>'userA3'));
        $this->assertEquals(1, $result);

        //插入一条语句,这字段名错误
        $result = Core::$_dbDriver->insert('pcore_system_test_a', array('titleNotExist'=>'titleA4','user'=>'userA4'));
        $this->assertFalse($result);
    
        $status = Core::$_dbDriver->rollBack();
        $this->assertTrue($status);
   
        //检索插入的数据没有插入成功
        $query = $conn->query('SELECT * FROM pcore_system_test_a');
        $newResults = $query->fetchAll(PDO::FETCH_ASSOC);
        //确认旧数据的内容一致
        $this->assertEquals($oldResults, $newResults);
    }
}
