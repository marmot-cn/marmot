<?php
namespace System\Observer;

use System\Interfaces;

/**
 * 全站观察者文件,需要统一函数update
 */

/**
 * 缓存memcache观察者
 * @author chloroplast1983
 *
 */
class CacheObserver implements Interfaces\Observer
{
    
    private $cacheCommand;
    
    public function __construct(Interfaces\Command $cacheCommand)
    {
        $this->cacheCommand = $cacheCommand;
    }
    public function update()
    {
        if ($this->cacheCommand instanceof Interfaces\Command) {
            $this->cacheCommand->undo();
        }
    }
}
