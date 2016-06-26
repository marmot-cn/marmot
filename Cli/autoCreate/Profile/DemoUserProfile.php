<?php

/**
 * 支持rule: 
 * 1.cellPhone: 手机
 * 2.qq: QQ号码
 * 3.email: 邮箱
 * 4.time: 时间
 * 5.数组: 状态码
 * 6.object: 对象
 */

return [
	'className' => 'User',
	'nameSpace' => 'User\Model',
	'comment' => '用户领域对象',
	'parameters' => [['key'=>'id','type'=>'int','rule'=>'int','default'=>0,'comment'=>'用户id'],
					 ['key'=>'password','type'=>'string','rule'=>'string','default'=>'','comment'=>'用户密码'],
					 ['key'=>'cellPhone','type'=>'string','rule'=>'cellPhone','default'=>'','comment'=>'用户手机号'],
					 // ['key'=>'qq','type'=>'string','rule'=>'qq','default'=>'','comment'=>'用户qq'],
					 // ['key'=>'email','type'=>'string','rule'=>'email','default'=>'','comment'=>'用户邮箱'],
					 ['key'=>'signUpTime','type'=>'int','rule'=>'time','default'=>'$_FWGLOBAL[\'timestamp\']','comment'=>'用户注册时间'],
					 ['key'=>'nickName','type'=>'string','rule'=>'string','default'=>'','comment'=>'用户昵称'],
					 ['key'=>'userName','type'=>'string','rule'=>'string','default'=>'','comment'=>'用户名'],
					 // ['key'=>'status','type'=>'int','rule'=>['USER_STATUS_NORMAL','USER_STATUS_BANNED'],'default'=>'USER_STATUS_NORMAL','comment'=>'用户状态'],
					 // ['key'=>'district','type'=>'Area\Model\Area','rule'=>'object','default'=>'','comment'=>'用户住址区']
					]
];
