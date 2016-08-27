<?php
namespace System\Query;

/**
 * SearchQuery文件,abstract抽象类.所有针对数据库搜索的类需要继承该类.
 *
 * 这里现在暂时使用数据库,后期数据量大的话可以考虑使用sphinx等第三方.
 * 这个类设计主要是用于解耦:
 * 1. rowQuery 和 rowCacheQuery 针对行的视角
 * 2. vectorQuery 和 vectorCacheQuery 针对关系的视角
 * 3. searchQuery 是针对查询关键词或者复杂的跨表关系使用
 *
 * @codeCoverageIgnore
 *
 * @author chloroplast
 * @version 1.0.0: 20160224
 */
abstract class SearchQuery
{

    /**
     * 根据条件查询匹配到条件的id数组
     *
     * @param array|string $condition 查询条件
     * @param integer $start 起始查询位置
     * @param integer $offset 偏移量
     *
     * @return [] 查询到的id数组
     */
    abstract public function find($condition, int $size, int $offset);
}
