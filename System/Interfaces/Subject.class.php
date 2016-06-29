<?php
namespace System\Interfaces;

/**
 * 观察者subject接口
 *
 * @codeCoverageIgnore
 *
 * @author chloroplast
 * @version 1.0: 20160222
 */
interface Subject
{

    /**
     * 增加一个新的观察者对象
     * @param Observer $observer
     */
    public function attach(Observer $observer);
 
    /**
     * 删除一个已注册过的观察者对象
     * @param Observer $observer
     */
    public function detach(Observer $observer);
 
    /**
     * 通知所有注册过的观察者对象
     */
    public function notifyObserver();
}
