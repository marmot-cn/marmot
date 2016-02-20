<?php
namespace System\Interfaces;
/**
 * 缓存层,用于接偶所有使用缓存的"具体"
 */

interface CacheLayer {

	//保存缓存
	function save($id, $data, $time=0);

	//删除缓存
	function del($id);

	//获取缓存数据
	function get($id);

	//批量获取缓存数据
	function getList($idList);
}
