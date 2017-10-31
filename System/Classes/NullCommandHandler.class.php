<?php
namespace System\Classes;

use System\Interfaces\ICommandHandler;
use System\Interfaces\ICommand;
use System\Interfaces\INull;

use Marmot\Core;

class NullCommandHandler implements ICommandHandler, INull
{
    public function execute(ICommand $command)
    {
        Core::setLastError(COMMAND_HANDLER_NOT_EXIST);
        return false;
    }
}
