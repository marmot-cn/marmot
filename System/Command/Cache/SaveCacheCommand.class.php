<?php
namespace System\Command\Cache;

use System\Interfaces;
use System\Observer\CacheObserver;
use System\Classes\Transaction;
use Marmot\Core;

/**
 * 添加cache缓存命令
 * @author chloroplast1983
 */

class SaveCacheCommand implements Interfaces\Command
{
    private $key;
    private $data;
    private $time;
    
    public function __construct($key, $data, $time = 0)
    {
        $this->key = $key;
        $this->data = $data;
        $this->time = $time;
        
        $this->attachedByObserver();
    }

    public function __destruct()
    {
        unset($this->key);
        unset($this->data);
        unset($this->time);
    }

    private function attachedByObserver() : bool
    {
        $transaction = Transaction::getInstance();
        if ($transaction->inTransaction()) {
            return $transaction->attachRollBackObserver(new CacheObserver($this));
        }
        return false;
    }

    public function execute() : bool
    {
        return Core::$cacheDriver->save($this->key, $this->data, $this->time);
    }

    public function undo() : bool
    {
        return Core::$cacheDriver->delete($this->key);
    }
}
