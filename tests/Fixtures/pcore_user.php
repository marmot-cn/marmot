<?php

$table = 'pcore_user';

$rows = 10;

$result = array();

$result[$table] = array();

for ($i = 1; $i<=$rows; $i++) {
    $user = \Member\Utils\ObjectGenerate::generateUser($i, $i);

    $row =  array(
                'user_id' => $user->getId(),
                'cellphone' => $user->getCellPhone(),
                'user_name' => $user->getUserName(),
                'nick_name' => $user->getNickName(),
                'password' => $user->getPassword(),
                'salt' => $user->getSalt(),
                'create_time' => $user->getCreateTime(),
                'update_time' => $user->getUpdateTime(),
                'status_time' => $user->getStatusTime(),
                'status' => $user->getStatus(),
                'real_name' => $user->getRealName()
            );
    $result[$table][] = $row;
}

return $result;
