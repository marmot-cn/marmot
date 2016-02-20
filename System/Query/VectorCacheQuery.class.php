<?php
/**
 * Query层的关系缓存缓存处理(注意是缓存关系,(xxx的xxx的xxx),不是搜索),主要用于匹配关系查询,关系匹配数据量大时使用,有如下两种方案:
 * 1.把关系缓存在mysql单独一张表内,做索引(只存储id),不推荐
 * 2.把关系缓存在redis内,对应使用list
 */
namespace System\Query;
use System\Classes;
use System\Interfaces;

abstract class VectorCacheQuery {

	protected $cacheLayer;//缓存层

	//可惜我没时间处理啊,主要需要考虑redis的排序,不过数据量大时在考虑
}
?>