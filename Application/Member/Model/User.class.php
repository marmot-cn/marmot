<?php
namespace Member\Model;

use Marmot\Core;
use User\Model\User as AbstractUser;

/**
 * 用户领域对象
 * @author chloroplast
 * @version 1.0.0: 20160222
 */

class User extends AbstractUser
{

    /**
     * @var string $realName 微信realName
     */
    private $realName;

    public function __construct(int $id = 0)
    {
        parent::__construct($id);
        $this->realName = '';
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->realName);
    }

    /**
     * 设置用户状态
     * @param int $status 用户状态
     */
    public function setStatus(int $status)
    {
        $this->status= in_array($status, array(
            STATUS_NORMAL,
            STATUS_DELETE)) ? $status : STATUS_NORMAL;
    }

    /**
     * 设置用户的真实姓名
     * @param string $realName
     */
    public function setRealName(string $realName)
    {
        $this->realName = $realName;
    }

    /**
     * 返回用户的真实姓名
     * @return string $realName
     */
    public function getRealName() : string
    {
        return $this->realName;
    }

    /**
     * 注册
     * @return bool 是否注册成功
     */
    public function signUp() : bool
    {
        $repository = Core::$container->get('Member\Repository\User\UserRepository');
        return $repository->add($this);
    }

    /**
     * 更新密码
     * @return bool 是否登陆成功
     */
    public function updatePassword() : bool
    {
        $repository = Core::$container->get('Member\Repository\User\UserRepository');
        return $repository->update($this, array(
                    'updateTime',
                    'password',
                    'salt',
                ));
    }
}
