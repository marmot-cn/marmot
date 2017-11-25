<?php
namespace User\Model;

use Marmot\Common\Model\Object;
use Marmot\Common\Model\IObject;

use Marmot\Core;

/**
 * 用户领域对象
 * @author chloroplast
 * @version 1.0.0: 20160222
 */
abstract class User implements IObject
{
    const SALT_LENGTH = 4;
    const SALT_BASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';

    /**
     * @var Object 对象性状
     */
    use Object;
    /**
     * @var int $id 用户uid
     */
    protected $id;
    /**
     * @var string $cellphone 用户手机号
     */
    protected $cellphone;
    /**
     * @var string $nickName 昵称
     */
    protected $nickName;
    /**
     * @var string $userName 用户名预留字段
     */
    protected $userName;
    /**
     * @var string $password 用户密码
     */
    protected $password;
    /**
     * @var string $salt 用户密码的盐
     */
    protected $salt;

    public function __construct(int $id = 0)
    {
        $this->id = !empty($id) ? $id : 0;
        $this->cellphone = '';
        $this->nickName = '';
        $this->userName = '';
        $this->password = '';
        $this->createTime = Core::$container->get('time');
        $this->updateTime = 0;
        $this->salt = '';
        $this->status = 0;
        $this->statusTime = 0;
    }

    public function __destruct()
    {
        unset($this->id);
        unset($this->cellphone);
        unset($this->nickName);
        unset($this->userName);
        unset($this->password);
        unset($this->createTime);
        unset($this->salt);
        unset($this->updateTime);
        unset($this->status);
        unset($this->statusTime);
    }
    
    /**
     * 设置用户id
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * 获取 id.
     *
     * @return $id 用户uid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 设置用户手机号码
     * @param string $cellphone
     */
    public function setCellphone($cellphone)
    {
        $this->cellphone = is_numeric($cellphone) ? $cellphone : '';
    }

    /**
     * Gets the value of cellphone.
     *
     * @return string $cellphone 用户名,现在用手机号
     */
    public function getCellphone() : string
    {
        return $this->cellphone;
    }

    /**
     * 设置用户密码
     *
     * @param string $password 用户密码
     * @param string $salt 盐
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * 加密用户密码
     * 如果盐不存在则生成盐
     * @param string $salt 盐
     * @return string 返回加密的密码
     */
    public function encryptPassword(string $password, string $salt = '')
    {
        //没有盐,自动生成盐
        $this->salt = empty($salt) ? $this->generateSalt() : $salt;
        $this->password = md5(md5($password).$this->salt);
    }

    /**
     * 随机生成 SALT_LENGTH 长度的盐
     *
     * @return string $salt 盐
     */
    private function generateSalt() : string
    {
        $salt = '';
        $max = strlen(self::SALT_BASE)-1;
        
        for ($i=0; $i<self::SALT_LENGTH; $i++) {
            $salt.=self::SALT_BASE[rand(0, $max)];
        }
        return $salt;
    }
    
    /**
     * Gets the value of password.
     *
     * @return string $password 用户密码
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * 设置salt
     * @param string $salt 盐
     */
    public function setSalt(string $salt)
    {
        $this->salt = $salt;
    }

    /**
     * Gets the value of salt.
     *
     * @return string $salt 用户密码的盐
     */
    public function getSalt() : string
    {
        return $this->salt;
    }

    /**
     * 设置昵称
     * @param string $nickName 昵称
     */
    public function setNickName(string $nickName)
    {
        $this->nickName = $nickName;
    }

    /**
     * 获取昵称
     * @return string $nickName 昵称
     */
    public function getNickName() : string
    {
        return $this->nickName;
    }

    /**
     * 设置用户名预留字段
     * @param string $userName 用户名预留字段
     */
    public function setUserName(string $userName)
    {
        $this->userName = $userName;
    }

    /**
     * 获取用户名预留字段
     * @return string $userName 用户名预留字段
     */
    public function getUserName() : string
    {
        return $this->userName;
    }

    /**
     * 修改密码
     */
    public function changePassword(string $oldPassword, string $newPassword) : bool
    {
        
        return $this->verifyPassword($oldPassword) && $this->updatePassword($newPassword);
    }

    /**
     * 注册
     */
    abstract public function signUp() : bool;

    abstract protected function verifyPassword(string $oldPassword) : bool;

    abstract protected function updatePassword(string $newPassword) : bool;
}
