<?php
namespace Member\Utils;

use Member\Model\User;

trait UserUtils
{
    private function compareArrayAndObject(
        array $expectedArray,
        $user
    ) {
        $this->assertEquals($expectedArray['user_id'], $user->getId());
        $this->assertEquals($expectedArray['cellphone'], $user->getCellPhone());
        $this->assertEquals($expectedArray['password'], $user->getPassword());
        $this->assertEquals($expectedArray['salt'], $user->getSalt());
        $this->assertEquals($expectedArray['create_time'], $user->getCreateTime());
        $this->assertEquals($expectedArray['update_time'], $user->getUpdateTime());
        $this->assertEquals($expectedArray['status'], $user->getStatus());
        $this->assertEquals($expectedArray['status_time'], $user->getStatusTime());
        $this->assertEquals($expectedArray['nick_name'], $user->getNickName());
        $this->assertEquals($expectedArray['user_name'], $user->getUserName());
        $this->assertEquals($expectedArray['real_name'], $user->getRealName());
    }

    private function compareModifiedArray(array $oldArray, array $newArray, array $keys = array())
    {
        if (in_array('id', $keys)) {
            $this->assertNotEquals($oldArray['user_id'], $newArray['user_id']);
        } else {
            $this->assertEquals($oldArray['user_id'], $newArray['user_id']);
        }
        
        if (in_array('cellPhone', $keys)) {
            $this->assertNotEquals($oldArray['cellphone'], $newArray['cellphone']);
        } else {
            $this->assertEquals($oldArray['cellphone'], $newArray['cellphone']);
        }
        
        if (in_array('password', $keys)) {
            $this->assertNotEquals($oldArray['password'], $newArray['password']);
        } else {
            $this->assertEquals($oldArray['password'], $newArray['password']);
        }
        
        if (in_array('salt', $keys)) {
            $this->assertNotEquals($oldArray['salt'], $newArray['salt']);
        } else {
            $this->assertEquals($oldArray['salt'], $newArray['salt']);
        }

        if (in_array('createTime', $keys)) {
            $this->assertNotEquals($oldArray['create_time'], $newArray['create_time']);
            $this->assertGreaterThan(0, $newArray['create_time']);
        } else {
            $this->assertEquals($oldArray['create_time'], $newArray['create_time']);
        }

        if (in_array('updateTime', $keys)) {
            $this->assertNotEquals($oldArray['update_time'], $newArray['update_time']);
            $this->assertGreaterThan(0, $newArray['update_time']);
        } else {
            $this->assertEquals($oldArray['update_time'], $newArray['update_time']);
        }

        if (in_array('status', $keys)) {
            $this->assertNotEquals($oldArray['status'], $newArray['status']);
        } else {
            $this->assertEquals($oldArray['status'], $newArray['status']);
        }

        if (in_array('statusTime', $keys)) {
            $this->assertNotEquals($oldArray['status_time'], $newArray['status_time']);
            $this->assertGreaterThan(0, $newArray['status_time']);
        } else {
            $this->assertEquals($oldArray['status_time'], $newArray['status_time']);
        }

        if (in_array('realName', $keys)) {
            $this->assertNotEquals($oldArray['real_name'], $newArray['real_name']);
        } else {
             $this->assertEquals($oldArray['real_name'], $newArray['real_name']);
        }
    }
}
