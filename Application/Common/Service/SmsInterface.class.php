<?php
namespace Common\Service;
/**
 * 通用信息短信角色接口
 * 
 * @codeCoverageIgnore
 * 
 * @author chloroplast
 * @version 1.0.20160223
 */
interface SmsInterface {

	//发送
	function send(string $cellPhone);
}