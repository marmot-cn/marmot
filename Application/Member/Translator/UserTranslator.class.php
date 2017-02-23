<?php
namespace Member\Translator;

use System\Classes\Translator;
use Member\Model\User;

class UserTranslator extends Translator
{

    protected function getMap() : array
    {
        return array(
            'id' => 'user_id',
            'cellPhone' => 'cellphone',
            'password' => 'password',
            'salt' => 'salt',
            'nickName' => 'nick_name',
            'userName' => 'user_name',
            'realName' => 'real_name',
            'createTime' => 'create_time',
            'updateTime' => 'update_time',
            'status' => 'status',
            'statusTime' => 'status_time'
        );
    }

    protected function getKeys() : array
    {
        return array(
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

    public function arrayToObject(array $expression, $user)
    {
        if (!$user instanceof User) {
            return false;
        }
        return parent::arrayToObject($expression, $user);
    }

    public function objectToArray($user, array $keys = array())
    {
        if (!$user instanceof User) {
            return false;
        }
        return parent::objectToArray($user, $keys);
    }
}
