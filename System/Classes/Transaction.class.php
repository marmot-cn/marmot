<?php
namespace System\Classes;

use System\Observer;
use Core;

/**
 * 全局事物控制,这个事务会把cache封装到同步到mysql事务内
 * 如果Mysql回滚了则cache也会回滚操作
 * @author chloroplast1983
 *
 */
class Transaction
{
    
    public static $transactionSubject = null;

    /**
     * 这里没有使用PDO::inTransaction,因为数据库连接已经设置为延迟加载.
     * 如果调用此方法需要初始化一次数据库连接.
     * 所以这里用程序来判断.
     */
    private static $inTransaction = false;//true 当前事务开启 false 当前事务关闭
    
    public static function beginTransaction()
    {
        //memcached观察者声明,用于在事务中使用保证cache数据和mysql数据一致
        //当时事务发生回滚时候,需要调用command undo
        self::$transactionSubject = new Observer\Subject();
        self::$inTransaction = true;
        return Core::$_dbDriver->beginTA();
    }

    public static function commit()
    {
        if (!Core::$_dbDriver->commit()) {
            return self::rollBack();
        }
        return true;
    }
    
    public static function inTransaction()
    {
        return self::$inTransaction;
    }
    
    public static function rollBack()
    {
        self::$transactionSubject -> notifyObserver();
        self::$inTransaction = false;//关闭事务
        self::$transactionSubject = null;//释放subject
        return Core::$_dbDriver->rollBack();
    }
}
