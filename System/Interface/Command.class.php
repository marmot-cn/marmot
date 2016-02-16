<?php

namespace System\Interface;
/**
 * 命令模式接口
 */
interface Command {
	/**
	 * 执行
	 */
	function execute();
	/**
	 * 回滚
	 */
	function undo();
}
?>