<?php
namespace System\Observer;

use System\Interfaces;

/**
 * @author chloroplast1983
 *
 */
class Subject implements Interfaces\Subject
{
    
    private $observers;
    
    public function __construct()
    {
        $this->_observers = array();
    }
 
    /**
     * 增加一个新的观察者对象
     * @param Observer $observer
     */
    public function attach(Interfaces\Observer $observer)
    {
        return array_push($this->_observers, $observer);
    }
 
    /**
     * 删除一个已注册过的观察者对象
     * @param Observer $observer
     */
    public function detach(Interfaces\Observer $observer)
    {
        $index = array_search($observer, $this->_observers);
        if ($index === false || ! array_key_exists($index, $this->_observers)) {
            return false;
        }
 
        unset($this->_observers[$index]);
        return true;
    }
 
    /**
     * 通知所有注册过的观察者对象
     */
    public function notifyObserver()
    {
        if (!is_array($this->_observers)) {
            return false;
        }
 
        foreach ($this->_observers as $observer) {
            $observer->update();
        }

        return true;
    }
}
