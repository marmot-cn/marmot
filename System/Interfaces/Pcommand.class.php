<?php
namespace System\Interfaces;

/**
 * 进程接口,所有子进程要执行该接口
 *
 * @codeCoverageIgnore
 *
 * @author chloroplast
 * @version 1.0: 20160222
 */
interface Pcommand
{
    /**
     * 执行进程
     *
     */
    public function execute();
    /**
     * 进程报告
     *
     */
    public function report();
}
