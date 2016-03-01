<?php
namespace Common\Controller;
use Common\Service;

/**
 * 通用应用层服务
 * @author chloroplast
 * @version 1.0.20160222
 */
class IndexController{


	/**
	 * 对应路由 /Common/registerSms/{cellPhone:\d+} 
	 * GET方法传参
	 * 
	 * 发送注册短信
	 * 
	 * @param string $cellPhone
	 */
	public function sendRegisterSms($cellPhone){

		var_dump($cellPhone);exit();
		$result = array (
            'result' => false,
            'errorCode' => 0,
            'reason' => NULL,
            'data' => array()
        );
        //数据传参校验 -- 开始
        //数据传参校验 -- 结束
        //领域服务调用 -- 开始
        $service = new Service\RegisterSmsService();
        $result['result'] = $service->send($cellPhone);
		//领域服务调用 -- 结束
        echo json_encode($result);
        exit();
	}

	/**
	 * 对应路由 /Common/registerSms/verify/{code:\d+}
	 * GET方法传参
	 * 
	 * 验证注册短信
	 * 
	 * @param string $code
	 */
	public function verifyRegisterSms($code){

		var_dump($code);exit();
		$result = array (
            'result' => false,
            'errorCode' => 0,
            'reason' => NULL,
            'data' => array()
        );
        //数据传参校验 -- 开始
        //数据传参校验 -- 结束
        //领域服务调用 -- 开始
        $service = new Service\RegisterSmsService();
        $result['result'] = $service->verify($code);
		//领域服务调用 -- 结束
        echo json_encode($result);
        exit();
	}

	/**
	 * 上传头像
	 */
	public function avatar(){

		$result = array (
            'result' => false,
            'errorCode' => 0,
            'reason' => NULL,
            'data' => array()
        );

        $service = new Service\AvatarFileService();
        $result['result'] = true;
        $result['data'] = $service->upload($_FILES['avatar']);
        echo json_encode($result);
        exit();
	}

	/**
	 * 对应路由 /Common/loginValidateImg/
	 * GET方法传参
	 * 登录图片验证码
	 */
	public function loginValidateImg(){

        //领域服务调用 -- 开始
        $service = new Service\SignValidateImgService();
        $service->generate();
		//领域服务调用 -- 结束
	}

	/**
	 * 验证登录验证码
	 */
	public function verifyLoginValidatImg($code){

     var_dump($_SESSION['signSession']);
     exit();
	}
}