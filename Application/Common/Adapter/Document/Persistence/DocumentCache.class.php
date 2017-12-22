<?php
namespace Common\Adapter\Document\Persistence;

use System\Classes\Cache;

/**
 * Document 缓存文件
 * @author chloroplast
 * @version 1.0.0: 20160223
 */
class DocumentCache extends Cache
{

    /**
     * 构造mongo 存储的key
     * @param string $db mongo 数据库
     * @param string $collection mongo 集合
     */
    public function __construct(string $db, string $collection)
    {
        parent::__construct($db.'_'.$collection);
    }
}
