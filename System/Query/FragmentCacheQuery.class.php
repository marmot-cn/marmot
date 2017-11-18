<?php
namespace System\Query;

use System\Classes;
use System\Interfaces;

/**
 * Query层的片段存处理,需要处理页面中的一个片段.
 *
 * 主要处理一些片段缓存,且这个片段缓存可能会集合若干个领域对象的数据.
 *
 * @author chloroplast
 * @version 1.0.0: 20160224
 */
abstract class FragmentCacheQuery
{
    private $fragmentKey;//片段缓存key名

    private $cacheLayer;//缓存层

    public function __construct(string $fragmentKey, Interfaces\CacheLayer $cacheLayer)
    {
        $this->fragmentKey = $fragmentKey;
        $this->cacheLayer = $cacheLayer;
    }

    public function __destruct()
    {
        unset($this->fragmentKey);
        unset($this->cacheLayer);
    }

    /**
     * 获取片段,如果缓存失效则必须重新更新该片段缓存
     */
    public function get()
    {
        //从缓存获取数据
        $cacheData = $this->cacheLayer->get($this->fragmentKey);
        if ($cacheData) {
            return $cacheData;
        }

        $cacheData = $this->refresh();
        if (!$cacheData) {
            return false;
        }

        return $cacheData;
    }
    /**
     * 更新缓存片段
     */
    abstract public function refresh();

    /**
     * 删除片段缓存
     */
    public function del()
    {
        $this->cacheLayer->del($this->fragmentKey);
    }
}
