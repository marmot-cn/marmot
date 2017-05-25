<?php
/**
 * 1 - 10000 通用错误
 * 90000 - 99999 产品服务错误
 */
define('COMMON_ERROR_LIMIT', 10000);
/**
 * 未定义错误
 */
define('ERROR_NOT_DEFINED', 0);
/**
 * 服务器错误
 */
define('INTERNAL_SERVER_ERROR', 1);
/**
 * 路由不存在
 */
define('ROUTE_NOT_EXIST', 2);
/**
 * 路由不支持该方法
 */
define('METHOD_NOT_ALLOWED', 3);
/**
 * 资源不存在
 */
define('RESOURCE_NOT_EXIST', 10);
