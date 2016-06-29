<?php
namespace System\Interfaces;

/**
 * 缓存层,用于接偶所有使用缓存的"具体"
 *
 * @codeCoverageIgnore
 *
 * @author chloroplast
 * @version 1.0: 20160222
 */
interface CacheLayer
{

    //保存缓存
    public function save($id, $data, $time = 0);

    //删除缓存
    public function del($id);

    //获取缓存数据
    public function get($id);

    //批量获取缓存数据
    public function getList($idList);
}
