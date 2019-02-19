<?php
namespace Member\Command\User;

use Marmot\Framework\Interfaces\ICommand;

class UpdatePasswordUserCommand implements ICommand
{
    /**
     * @var string oldPassword 旧密码
     */
    public $oldPassword;
    /**
     * @var string password 密码
     */
    public $password;
    /**
     * @var int uid 用户id
     */
    public $uid;

    public function __construct(string $oldPassword, string $password, int $uid)
    {
        $this->oldPassword = $oldPassword;
        $this->password = $password;
        $this->uid = $uid;
    }
}
