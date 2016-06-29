<?php
namespace System\Classes;

/**
 * 测试框架封装的事务类,这个事务会把memcached封装到同步到mysql事务内
 * 如果Mysql回滚了所以我们需要测试:
 * 1. 如果事务正常提交,数据库正常提交,则cache正常存储
 * 2. 如果事务回滚,数据库回滚,则cache回滚
 */
class TransactionTest extends GenericTestsDatabaseTestCase
{

    public $fixtures = array('pcore_system_test_a','pcore_system_test_b');

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * 测试MyPdo的事务功能commit
     */
    public function testTransactionCommit()
    {

        $ids = array();
        //查出旧数据
        $conn = $this->getConnection()->getConnection();
        $query = $conn->query('SELECT * FROM pcore_system_test_a');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $oldCount = sizeof($results);

        //开启事务
        System\Classes\Transaction::beginTransaction();
        //插入一条语句
        Core::$_dbDriver->insert('pcore_system_test_a', array('title'=>'titleA3','user'=>'userA3'));
        //保存到ids[]数组,因为要添加多条数据需要测试从缓存读取数据
        $ids[] = Core::$_dbDriver->lastInertId();
        
        //保存数据到缓存,key为主键id
        $command = new System\Command\Cache\SaveCacheCommand(
            $ids[0],
            array('id'=>$ids[0],'title'=>'titleA3','user'=>'userA3')
        );
        $command -> execute();
        // var_dump($ids);exit();
        //插入一条语句
        Core::$_dbDriver->insert('pcore_system_test_a', array('title'=>'titleA4','user'=>'userA4'));
        //保存到ids[]数组,因为要添加多条数据需要测试从缓存读取数据
        $ids[] = Core::$_dbDriver->lastInertId();
        //保存数据到缓存,key为主键id
        $command = new System\Command\Cache\SaveCacheCommand(
            $ids[1],
            array('id'=>$ids[1],'title'=>'titleA4','user'=>'userA4')
        );
        $command -> execute();
        //commit提交
        $status = System\Classes\Transaction::Commit();
        $this->assertTrue($status);
        //检索插入的数据已经插入成功
        //检索总数据数量为旧的总数+2
        $results = Core::$_dbDriver->query('SELECT * FROM pcore_system_test_a');
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

        //从缓存检索插入数据,检查内容是否匹配
        $data = Core::$_cacheDriver->fetch($ids[0]);
        $this->assertEquals(4, $data['id']);
        $this->assertEquals('titleA3', $data['title']);
        $this->assertEquals('userA3', $data['user']);

        $data = Core::$_cacheDriver->fetch($ids[1]);
        $this->assertEquals(5, $data['id']);
        $this->assertEquals('titleA4', $data['title']);
        $this->assertEquals('userA4', $data['user']);

    }

    /**
     * 测试MyPdo的事务功能回滚
     */
    public function testTransactionRollBack()
    {
 
        $ids = array();
        //查出旧数据
        $oldResults = Core::$_dbDriver->query('SELECT * FROM pcore_system_test_a');
        $oldCount = sizeof($oldResults);

        //开启事务
        System\Classes\Transaction::beginTransaction();
        //插入一条语句
        Core::$_dbDriver->insert('pcore_system_test_a', array('title'=>'titleA3','user'=>'userA3'));
        //保存到ids[]数组,因为要添加多条数据需要测试从缓存读取数据
        $ids[] = Core::$_dbDriver->lastInertId();

        //保存数据到缓存,key为主键id
        $command = new System\Command\Cache\SaveCacheCommand(
            $ids[0],
            array('id'=>$ids[0],'title'=>'titleA3','user'=>'userA3')
        );
        $command -> execute();
        //插入一条语句
        Core::$_dbDriver->insert('pcore_system_test_a', array('title'=>'titleA4','user'=>'userA4'));
        //保存到ids[]数组,因为要添加多条数据需要测试从缓存读取数据
        $ids[] = Core::$_dbDriver->lastInertId();
        //保存数据到缓存,key为主键id
        $command = new System\Command\Cache\SaveCacheCommand(
            $ids[1],
            array('id'=>$ids[1],'title'=>'titleA4','user'=>'userA4')
        );
        $command -> execute();
        //回滚
        $status = System\Classes\Transaction::rollBack();
        $this->assertTrue($status);
        //检索插入的数据没有插入成功
        $newResults = Core::$_dbDriver->query('SELECT * FROM pcore_system_test_a');

        //确认旧数据的内容一致
        $this->assertEquals($oldResults, $newResults);

        //从缓存检索插入数据,检查内容是否为空
        $data = Core::$_cacheDriver->fetch($ids[0]);
        $this->assertEmpty($data);

        $data = Core::$_cacheDriver->fetch($ids[1]);
        $this->assertEmpty($data);
    }
}
