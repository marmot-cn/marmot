<?php
namespace Common\Adapter\Document\Persistence;

use Marmot\Framework\Classes\Cache;

/**
 * Document 缓存文件
 * @author chloroplast
 * @version 1.0.0: 20160223
 */
class DocumentCache extends Cache
{

    /**
     * 构造mongo 存储的key
     * @param string $dbName mongo 数据库
     * @param string $collection mongo 集合
     */
    public function __construct(string $dbName, string $collection)
    {
        parent::__construct($dbName.'_'.$collection);
    }
}
