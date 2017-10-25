<?php
namespace Member\Model;

use System\Interfaces\INull;
use Marmot\Core;

class NullUser extends User implements INull
{
    private static $instance;
    
    public static function &getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function resourceNotExist() : bool
    {
        Core::setLastError(RESOURCE_NOT_EXIST);
        return false;
    }

    public function isNormal() : bool
    {
        return $this->resourceNotExist();
    }

    public function isDelete() : bool
    {
        return $this->resourceNotExist();
    }

    public function changePassword(string $oldPassword, string $newPassword) : bool
    {
        return $this->resourceNotExist();
    }
}
