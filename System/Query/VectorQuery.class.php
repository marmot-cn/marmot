<?php
namespace System\Query;

use System\Classes;
use System\Interfaces;

/**
 * 数据库DBVectoryQuery层(针对关系处理).
 * Query层的关系缓存缓存处理(注意是缓存关系,(xxx的xxx的xxx),不是搜索).
 * 主要用于匹配关系查询,关系匹配数据量大时使用
 * 把关系缓存在mysql单独一张表内,做索引(只存储id),不推荐,临时解决方案.
 * 数据量过大时候请使用VectorCacheQuery(redis),
 * 替换$dbLayer层即可.
 *
 * @codeCoverageIgnore
 *
 * @author chloroplast
 * @version 1.0.0: 20160224编写
 */
abstract class VectorQuery
{

    /**
     * 根据条件查询匹配到条件的id数组
     *
     * @param string $condition 查询条件
     * @param integer $start 起始查询位置
     * @param integer $offset 偏移量
     *
     * @return [] 查询到的id数组
     */
    abstract public function find(string $condition, int $size, int $offset);
}
