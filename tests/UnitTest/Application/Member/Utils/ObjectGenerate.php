<?php
namespace Member\Utils;

use Member\Model\User;
use Member\Model\Crew;
use Member\Model\Account;

class ObjectGenerate
{

    public static function generateUser(int $id = 0, int $seed = 0, array $value = array()) : User
    {
        $faker = \Faker\Factory::create('zh_CN');
        $faker->seed($seed);//设置seed,放置和生成数据相同
        $user = new User($id);

        //cellphone
        $cellphone = isset($value['cellphone']) ? $value['cellphone'] : $faker->phoneNumber;
        $user->setCellphone($cellphone);

        //password,salt
        $password = isset($value['password']) ? $value['password'] : $faker->password;
        $salt = isset($value['salt']) ? $value['salt'] : $faker->bothify('##??');
        $user->encryptPassword($password, $salt);

        $user->setCreateTime($faker->unixTime());
        $user->setUpdateTime($faker->unixTime());
        $user->setStatusTime($faker->unixTime());

        //status
        $status = isset($value['status']) ? $value['status'] : $faker->randomElement(
            $array = array(
                            User::STATUS_NORMAL,
                            User::STATUS_DELETE
                        )
        );
        $user->setStatus($status);

        //nickName
        $nickName = isset($value['nickName']) ? $value['nickName'] : $faker->userName;
        $user->setNickName($nickName);

        //userName
        $userName = isset($value['userName']) ? $value['userName'] : $faker->userName;
        $user->setUserName($userName);
        
        //realName
        $realName = isset($value['realName']) ? $value['realName'] : $faker->name;
        $user->setRealName($realName);

        return $user;
    }
}
