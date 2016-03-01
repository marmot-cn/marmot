<?php
namespace User\Model;
use Area\Model\Area;

/**
 * 用户领域对象
 * @author chloroplast
 * @version 1.0.0: 20160222
 */

class User {
    
    /**
     * @var int $id 用户uid
     */
    private $id;
    /**
     * @var string $cellPhone 用户名,现在用手机号
     */
    private $cellPhone;
    /**
     * @var string $password 用户密码
     */
    private $password;  
    /**
     * @var string $salt 用户密码的盐
     */
    private $salt;
    /**
     * @var int $avatar 用户头像图片文件id
     */
    private $avatar;
    /**
     * @var string $realName 用户真实姓名
     */
    private $realName;
    /**
     * @var Area $province 地区对象,省
     */
    private $province;
    /**
     * @var Area $city 地区对象,市
     */
    private $city;
    /**
     * @var Area $district 地区对象,地区
     */
    private $district;
    /**
     * @var string $colleage 用户所在学校
     */
    private $colleage;
    /**
     * @var string $birthday 用户出生日期
     */
    private $birthday;
    /**
     * @var int $gender 用户性别 1.男 2.女 0.未设置
     */
    private $gender;
    /**
     * @var int $subject 科目 1.文科 2.理科 0.未设置
     */
    private $subject;
    /**
     * @var string $email 用户邮箱
     */
    private $email;
    /**
     * @var string $qq qq号码
     */
    private $qq;
    /**
     * @var int $createTime 用户注册时间戳
     */
    private $createTime;
    /**
     * @var int $status 用户状态,现在预留 0.正常 -1.禁用
     */
    private $status;
    /**
     * @var int $lastScore 上一次输入的考试分数
     */
    private $lastScore;

    /**
     * @var bool $IsVip 是否Vip
     */
    private $isVip;

    public function __construct(){
        global $_FWGLOBAL;
        $this->id = 0;
        $this->cellPhone = '';
        $this->password = '';
        $this->salt = '';
        $this->avatar = 0;
        $this->realName = '';
        $this->province = new Area();
        $this->city = new Area();
        $this->district = new Area();
        $this->colleage = '';
        $this->birthday = '';
        $this->gender = 0;
        $this->subject = 0;
        $this->qq = '';
        $this->email = '';
        $this->createTime = $_FWGLOBAL['timestamp'];
        $this->status = 0;
        $this->lastScore = 0;
        $this->isVip = false;
    }

    public function __destruct(){
        unset($this->id);
        unset($this->cellPhone);
        unset($this->password);
        unset($this->salt);
        unset($this->avatar);
        unset($this->realName);
        unset($this->province);
        unset($this->city);
        unset($this->district);
        unset($this->colleage);
        unset($this->birthday);
        unset($this->gender);
        unset($this->subject);
        unset($this->qq);
        unset($this->email);
        unset($this->createTime);
        unset($this->status);
        unset($this->lastScore);
        unset($this->isVip);
    }
    
    /**
     * 设置用户id
     * @param int $id
     */
    public function setId(int $id){
        $this->id = $id;
    }   

    /**
     * Gets the value of id.
     *
     * @return int $id 用户uid
     */
    public function getId(){
        return $this->id;
    }

    /**
     * 设置用户手机号码
     * @param string $cellPhone
     */
    public function setCellPhone(string $cellPhone){
        $this->cellPhone = is_numeric($cellPhone) ? $cellPhone : '';
    }

    /**
     * Gets the value of cellPhone.
     *
     * @return string $cellPhone 用户名,现在用手机号
     */
    public function getCellPhone(){
        return $this->cellPhone;
    }

    /**
     * 设置用户密码
     * 
     * @param string $password 用户密码
     * @param string $salt 盐
     */
    public function setPassword(string $password,string $salt=''){

        //判断盐的长度是否为4
        $salt = strlen($salt) == 4 ? $salt : '';

        //赋值密码
        $this->password = $password;
        //赋值加密后的密码
        $this->password = $this->encryptPassword($salt);
    }

    /**
     * 生成盐给用户加密
     * @param string $salt 盐
     * @return string 返回加密的密码
     */
    private function encryptPassword(string $salt = ''){
        //没有gei获取盐
        $this->salt = empty($salt) ? $this->generateSalt() : $salt;
        
        return md5(md5($this->password).$this->salt);
    }

    /**
     * 随机生成 SALT_LENGTH 长度的盐
     * 
     * @return string $salt 盐
     */
    private function generateSalt(){
        $salt = '';
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0; $i<SALT_LENGTH; $i++){
            $salt.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $salt;
    }
    /**
     * Gets the value of password.
     *
     * @return string $password 用户密码
     */
    public function getPassword(){
        return $this->password;
    }

    /**
     * Gets the value of salt.
     *
     * @return string $salt 用户密码的盐
     */
    public function getSalt(){
        return $this->salt;
    }

    /**
     * 给用户设置头像文件id
     * @param int $avatar 头像图片文件id
     */
    public function setAvatar(int $avatar){
        $this->avatar = $avatar;
    }

    /**
     * Gets the value of avatar.
     *
     * @return int $avatar 用户头像图片文件id
     */
    public function getAvatar(){
        return $this->avatar;
    }

    /**
     * 设置用户真实姓名
     * @param string $realName 用户真实姓名
     */
    public function setRealName(string $realName){
        $this->realName = $realName;
    }

    /**
     * Gets the value of realName.
     *
     * @return string $realName 用户真实姓名
     */
    public function getRealName(){
        return $this->realName;
    }

    /**
     * 设置省
     * @param Area $province
     */
    public function setProvince(Area $province){
        $this->province = $province;
    }
    /**
     * Gets the value of province.
     *
     * @return Area $province 地区对象,省
     */
    public function getProvince(){
        return $this->province;
    }

    /**
     * 设置市
     * @param Area $city
     */
    public function setCity(Area $city){
        $this->city = $city;
    }
    /**
     * Gets the value of city.
     *
     * @return Area $city 地区对象,市
     */
    public function getCity(){
        return $this->city;
    }

    /**
     * 设置区
     * @param Area $district
     */
    public function setDistrict(Area $district){
        $this->district = $district;
    }
    /**
     * Gets the value of district.
     *
     * @return Area $district 地区对象,地区
     */
    public function getDistrict(){
        return $this->district;
    }

    public function setEmail(string $email){
        $this->email = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
    }
    /**
     * Gets the value of email.
     *
     * @return string $email 用户邮箱
     */
    public function getEmail(){
        return $this->email;
    }

    public function setQq(string $qq){
        $this->qq = is_numeric($qq) ? $qq : '';
    }
    /**
     * Gets the value of qq.
     *
     * @return string $qq qq号码
     */
    public function getQq(){
        return $this->qq;
    }

    public function setCreateTime(int $createTime){
        $this->createTime = $createTime;
    }

    /**
     * Gets the value of createTime.
     *
     * @return int $createTime 用户注册时间戳
     */
    public function getCreateTime(){
        return $this->createTime;
    }

    /**
     * 设置用户状态,预留
     * 
     * @param int $status 用户状态
     */
    public function setStatus(int $status){
        $this->status = in_array($status,array(USER_STATUS_NORMAL,USER_STATUS_BANNED)) ? $status : USER_STATUS_NORMAL;
    }
    /**
     * Gets the value of status.
     *
     * @return int $status 用户状态,现在预留 0.正常 -1.禁用
     */
    public function getStatus(){
        return $this->status;
    }

}