<?php
namespace System\Observer;
use System\Interface;
/**
 * memcache 观察者声明,用于在事务中使用保证cache数据和mysql数据一致
 * 当时事务发生回滚时候,需要调用command undo
 * @author chloroplast1983
 *
 */
class TransactionSubject implements Subject {
	
    private $_observers;
 	
    public function __construct() {
        $this->_observers = array();
    }
 
    /**
     * 增加一个新的观察者对象
     * @param Observer $observer
     */
    public function attach(Observer $observer) {
        return array_push($this->_observers, $observer);
    }
 
    /**
     * 删除一个已注册过的观察者对象
     * @param Observer $observer
     */
    public function detach(Observer $observer) {
        $index = array_search($observer, $this->_observers);
        if ($index === FALSE || ! array_key_exists($index, $this->_observers)) {
            return FALSE;
        }
 
        unset($this->_observers[$index]);
        return TRUE;
    }
 
    /**
     * 通知所有注册过的观察者对象
     */
    public function notifyObserver() {
        if (!is_array($this->_observers)) {
            return FALSE;
        }
 
        foreach ($this->_observers as $observer) {
            $observer->update();
        }
 
        return TRUE;
    }

}
?>