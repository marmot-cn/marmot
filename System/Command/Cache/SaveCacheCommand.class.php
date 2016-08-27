<?php
namespace System\Command\Cache;

use System\Interfaces;
use System\Observer;
use System\Classes;
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
        
        if (Classes\Transaction::inTransaction()) {
            Classes\Transaction::$transactionSubject -> attach(new Observer\CacheObserver($this));
        }
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
