<?php
namespace Member\Translator;

use System\Interfaces\ITranslator;
use Member\Model\User;

class UserDataBaseTranslator implements ITranslator
{
    public function arrayToObject(array $expression)
    {
        $user = new User($expression['user_id']);
        $user->setCellPhone($expression['cellphone']);
        $user->setPassword($expression['password']);
        $user->setSalt($expression['salt']);
        $user->setCreateTime($expression['create_time']);
        $user->setUpdateTime($expression['update_time']);
        $user->setNickName($expression['nick_name']);
        $user->setUserName($expression['user_name']);
        $user->setStatus($expression['status']);
        $user->setStatusTime($expression['status_time']);
        $user->setRealName($expression['real_name']);

        return $user;
    }

    public function objectToArray($user, array $keys = array())
    {
        if (!$user instanceof User) {
            return false;
        }

        if (empty($keys)) {
            $keys = array(
                'id',
                'cellPhone',
                'updateTime',
                'createTime',
                'statusTime',
                'status',
                'nickName',
                'userName',
                'password',
                'salt',
                'realName'
            );
        }

        $expression = array();

        if (in_array('id', $keys)) {
            $expression['user_id'] = $user->getId();
        }
        if (in_array('cellPhone', $keys)) {
            $cellPhone = $user->getCellPhone();
            if (!empty($cellPhone)) {
                $expression['cellphone'] = $cellPhone;
            }
        }

        if (in_array('password', $keys)) {
            $expression['password'] = $user->getPassword();
        }

        if (in_array('salt', $keys)) {
            $expression['salt'] = $user->getSalt();
        }

        if (in_array('nickName', $keys)) {
            $expression['nick_name'] = $user->getNickName();
        }

        if (in_array('userName', $keys)) {
            $expression['user_name'] = $user->getUserName();
        }

        if (in_array('realName', $keys)) {
            $expression['real_name'] = $user->getRealName();
        }

        if (in_array('createTime', $keys)) {
            $expression['create_time'] = $user->getCreateTime();
        }

        if (in_array('updateTime', $keys)) {
            $expression['update_time'] = $user->getUpdateTime();
        }

        if (in_array('status', $keys)) {
            $expression['status'] = $user->getStatus();
        }

        if (in_array('statusTime', $keys)) {
            $expression['status_time'] = $user->getStatusTime();
        }

        return $expression;
    }
}
