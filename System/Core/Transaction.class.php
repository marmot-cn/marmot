<?php
namespace System\Core;
/**
 * 全局事物控制
 * @author chloroplast1983
 *
 */
class Transaction {
	
	public static $transactionSubject = null;

	/**
	 * 这里没有使用PDO::inTransaction,因为数据库连接已经设置为延迟加载.如果调用此方法需要初始化一次数据库连接.
	 * 所以这里用程序来判断.
	 */
	protected static $inTransaction = false;//true 当前事务开启 false 当前事务关闭
	
	public static function startTransaction(){
		self::$transactionSubject = new TransactionSubject();
		self::$inTransaction = true;
		DBW::query('START TRANSACTION');
	}

	public static function endTransaction(){
		global $_FWGLOBAL;
		if(DBW::errno()){
			self::rollBack();
			return false;
		}else{
			self::$inTransaction = false;//关闭事务
			DBW::query("COMMIT");
			return true;
		}
	}
	
	public static function inTransaction(){
		return self::$inTransaction;
	}
	public static function rollBack(){
		self::$transactionSubject -> notifyObserver();
		self::$inTransaction = false;//关闭事务
		self::$transactionSubject = null;//释放subject
		DBW::query("ROLLBACK");
	}
}
?>