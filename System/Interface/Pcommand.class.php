<?php
namespace System\Interface;
/**
 * 进程接口,所有子进程要执行该接口
 *
 */
interface Pcommand {
	/**
	 * 执行进程
	 *
	 */
	function execute();
	/**
	 * 进程报告
	 *
	 */
	function report();
}


?>