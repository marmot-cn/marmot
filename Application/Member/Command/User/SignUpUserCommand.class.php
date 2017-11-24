<?php
namespace Member\Command\User;

use System\Interfaces\ICommand;

class SignUpUserCommand implements ICommand
{
    /**
     * @var string cellphone 手机号
     */
    public $cellphone;
    /**
     * @var string  password 密码
     */
    public $password;
    /**
     * @var int $uid 注册用户id,回填
     */
    public $uid;

    public function __construct(
        string $cellphone,
        string $password,
        int $uid = 0
    ) {
        $this->cellphone = $cellphone;
        $this->password = $password;
        $this->uid = $uid;
    }
}
