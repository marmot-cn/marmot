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
    const STATUS_NORMAL = 0;
    const STATUS_DELETE = -2;

    /**
     * @var string $realName 微信realName
     */
    private $realName;

    public function __construct(int $id = 0)
    {
        parent::__construct($id);
        $this->realName = '';
        $this->status  = self::STATUS_NORMAL;
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
        $this->status= in_array(
            $status,
            array(
                self::STATUS_NORMAL,
                self::STATUS_DELETE
            )
        ) ? $status : self::STATUS_NORMAL;
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
   
    public function isNormal() : bool
    {
        return $this->getStatus() == self::STATUS_NORMAL;
    }

    public function isDelete() : bool
    {
        return $this->getStatus() == self::STATUS_DELETE;
    }

    /**
     * 注册
     * @return bool 是否注册成功
     */
    public function signUp() : bool
    {
        $repository = Core::$container->get('Member\Repository\User\UserRepository');
        if (!$repository->add($this)) {
            Core::setLastError(USER_IDENTIFY_DUPLICATE);
            return false;
        }
        return true;
    }

    /**
     * 更新密码
     * @return bool 是否登陆成功
     */
    public function updatePassword(string $password) : bool
    {
        $this->encryptPassword($password);
        
        $repository = Core::$container->get('Member\Repository\User\UserRepository');
        return $repository->update($this, array(
                    'updateTime',
                    'password',
                    'salt',
                ));
    }

    public function verifyPassword(string $oldPassword) : bool
    {
        //检查旧密码是否正确
        $oldEncryptedPassword = $this->getPassword();
        $this->encryptPassword($oldPassword, $this->getSalt());
        if ($oldEncryptedPassword != $this->getPassword()) {
            Core::setLastError(USER_OLD_PASSWORD_NOT_CORRECT);
            return false;
        }

        return true;
    }
}
