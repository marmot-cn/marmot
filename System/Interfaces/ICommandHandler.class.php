<?php
namespace System\Interfaces;

/**
 * CommandHandler 接口
 *
 * @codeCoverageIgnore
 *
 * @author chloroplast
 * @version 1.0: 20160828
 */
interface ICommandHandler
{
    /**
     * 执行
     */
    public function execute(ICommand $command);
}
