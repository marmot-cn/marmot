<?php
/**
 *  
 * 路由设置
 */
return [
	//common
	['method'=>'GET','rule'=>'/common/loginValidateImg','controller'=>['Common\Controller\IndexController','loginImg']],
	['method'=>'GET','rule'=>'/common/loginValidateImg/verify/{code}','controller'=>['Common\Controller\IndexController','verifyLoginImg']],
	['method'=>'POST','rule'=>'/common/avatar','controller'=>['Common\Controller\IndexController','avatar']],
	//user
	['method'=>'GET','rule'=>'/user/{id:\d+}','controller'=>['User\Controller\IndexController','get']],
	['method'=>'POST','rule'=>'/user/signUp','controller'=>['User\Controller\IndexController','signUp']],
	['method'=>'POST','rule'=>'/user/signIn','controller'=>['User\Controller\IndexController','signIn']],
	['method'=>'PUT','rule'=>'/user/{id:\d+}/updateProfile','controller'=>['User\Controller\IndexController','updateProfile']],
	['method'=>'GET','rule'=>'/user/{id:\d+}/updatePassword','controller'=>['User\Controller\IndexController','updatePassword']],
	['method'=>'POST','rule'=>'/user/vaildateCellPhone/{cellPhone}','controller'=>['User\Controller\IndexController','validateCellPhone']],
	['method'=>'GET','rule'=>'/user/restPassword/','controller'=>['User\Controller\IndexController','validateCellPhone']],
];

?>