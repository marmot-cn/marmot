<?php

//用户状态
/**
 * @var int USER_STATUS_NORMAL  用户状态  正常
 */
define('USER_STATUS_NORMAL',0);

/**
 * @var int USER_STATUS_BANNED  用户状态  禁用
 */
define('USER_STATUS_BANNED',-1);

//盐长度
/**
 * @var int SALT_LENGTH 盐长度
 */
define('SALT_LENGTH',4);

//手机短信
/**
 * @var string SMS_REGISTER_MESSAGE 手机注册短信
 */
define('SMS_REGISTER_MESSAGE','手机短信注册验证码为[%s]');
?>