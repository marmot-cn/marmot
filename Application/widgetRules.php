<?php
namespace Application;

class WidgetRules
{
    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public static function id(int $id, int $errorCode)
    {
        return [$id,'int','min:1',$errorCode];
    }
}
