<?php
namespace User\Controller;
/**
 * 用户应用层服务
 * @author chloroplast
 * @version 1.0.20160222
 */
class IndexController{

	/**
	 * 对应路由 /user/{id:\d+} 
	 * GET方法传参
	 * 根据用户id获取用户详情,该接口用于:
	 * 1. 任何获取用户信息页面
	 * 2. 判断用户是否为vip,先通过该接口获取详情,再判断.
	 * 
	 * @example /user/1 获取用户id为1的信息
	 * 
	 * @param int $id 用户id
	 * @return array('result'=>bool | false,
	 * 				 'errorCode'=>(暂时全设置为0,后续写配置文件),
	 * 				 'reason'=>对应errorCode的错误原因,现在暂时为手写,
	 * 				 'data'=>array('avatar'=>(string)头像图片路径,
	 * 							   'cellPhone'=>(string)登录账号,用户手机号,
	 * 							   'realName'=>(string)真实姓名,
	 * 							   'provinceId'=>(int)省id,
	 * 							   'cityId'=>(int)市id,
	 * 							   'districtId'=>(int)区id,
	 * 							   'email'=>(string)邮箱 xxxx@xx.com
	 * 							   'qq'=>(string)qq号码 41893204,
	 * 				 )
	 */
	public function get($id){

		var_dump('user-get:'.$id);exit();
		$result = array (
            'result' => false,
            'errorCode' => 0,
            'reason' => NULL,
            'data' => array()
        );
        //数据传参校验 -- 开始
        //数据传参校验 -- 结束
        //领域服务调用 -- 开始
		$repository = Core::$_container->get('Repository\UserRepository');
        $result['data'] = $repository -> getOne($id);
		//领域服务调用 -- 结束
        echo json_encode($result);
        exit();
	}

	/**
	 * 对应路由 /user/signUp (/user/signUp)
	 * 用户注册功能,通过post传参
	 * @param string cellPhone 手机号,手机为11位
	 * @param string password 密码 (6-18位,a-Zand0-9),
	 * @param string rePassword 重复密码
	 * @param string code 验证码,短信验证码
	 * @return array('result'=>bool true | false,
	 * 				 'errorCode'=>(暂时全设置为0,后续写配置文件),
	 * 				 'reason'=>对应errorCode的错误原因,现在暂时为手写,
	 * 				 'data'=>array())
	 */
	public function signUp(){

		print_r('user/signUp');
		print_r($_POST);

		exit();

		$result = array (
            'result' => false,
            'errorCode' => 0,
            'reason' => NULL,
            'data' => array()
        );
        //数据传参校验 -- 开始
		//手机号是否为11位
		//password
		//v::stringType()->length(6,18)->validate('abc');
		//v::alnum()->noWhitespace()->validate('foo 123');
		//password 和 rePassword 是否一致
		$cellPhone = '';
		$password = '';
        //数据传参校验 -- 结束
        //领域服务构建 -- 开始
        $service = new User\Service\GuestService();
        $result['result'] = $service->signUp($cellPhone,$password);
		//领域服务构建 -- 结束
        echo json_encode($result);
        exit();
	}

	/**
	 * /user/signIn/ (/user/signIn)
	 * 用户登录功能,通过post传参
	 * @param string cellPhone 手机号,手机为11位
	 * @param string password 密码 (6-18位,a-Zand0-9),
	 * @param string code 验证码,图片验证码,
	 * @return array('result'=>bool true | false,
	 * 				 'errorCode'=>(暂时全设置为0,后续写配置文件),
	 * 				 'reason'=>对应errorCode的错误原因,现在暂时为手写,
	 * 				 'data'=>array())
	 */
	public function signIn(){

		print_r('user/signIn');
		print_r($_POST);

		exit();

		$result = array (
            'result' => false,
            'errorCode' => 0,
            'reason' => NULL,
            'data' => array()
        );
		//数据传参校验 -- 开始
		//cellPhone不能为空
		//password 验证
		//code 不为空
        //数据传参校验 -- 结束
        //功能构建 -- 开始
		//功能构建 -- 结束
	}

	/**
	 * 对应路由 /user/{id:\d+}/updateProfile
	 * 更新用户信息,通过PUT传参,json格式
	 * @param string realName 真实姓名 
	 * @param int provinceId 省id
	 * @param int cityId 市id
	 * @param int districtId 区id
	 * @param string email 
	 * @param string qq
	 * 
	 * @return array('result'=>bool true | false,
	 * 				 'errorCode'=>(暂时全设置为0,后续写配置文件),
	 * 				 'reason'=>对应errorCode的错误原因,现在暂时为手写,
	 * 				 'data'=>array())
	 */
	public function updateProfile($id){
		print_r('user/updateProfile');
		echo 'id:'.$id;

		$josn = file_get_contents('php://input');
		$_PUT = json_decode($json,true);
		print_r($_PUT);
		exit();

		$result = array (
            'result' => false,
            'errorCode' => 0,
            'reason' => NULL,
            'data' => array()
        );
        //数据传参校验 -- 开始
        //数据传参校验 -- 结束
        //领域服务调用 -- 开始
        $service = new User\Service\MemberService();
        $result['result'] = $service->updateProfile($id,$realName,$provinceId,$cityId,$districtId,$email,$qq);
		//领域服务调用 -- 结束
        echo json_encode($result);
        exit();
	}

	/**
	 * 对应路由 /user/{id:\d+}/updatePassword
	 * 更新用户密码,通过PUT传参,json
	 * @param string id 用户id
	 * @param string cellPhone,手机为11位
	 * @param string oldPassword 旧密码 (6-18位,a-Z and 0-9),
	 * @param string rePassword 重复密码
	 * @param string password 新密码
	 * @return array('result'=>bool true | false,
	 * 				 'errorCode'=>(暂时全设置为0,后续写配置文件),
	 * 				 'reason'=>对应errorCode的错误原因,现在暂时为手写,
	 * 				 'data'=>array())
	 */
	public function updatePassword($id){

		print_r('user/updatePassword');
		echo 'id:'.$id;

		$josn = file_get_contents('php://input');
		$_PUT = json_decode($json,true);
		print_r($_PUT);
		exit();

		$result = array (
            'result' => false,
            'errorCode' => 0,
            'reason' => NULL,
            'data' => array()
        );
        //数据传参校验 -- 开始
        $id = '';
        $password = '';
        //数据传参校验 -- 结束
        //领域服务调用 -- 开始
		$service = new User\Service\MemberService();
		$service->updatePassword($id,$password);
		//领域服务调用 -- 结束
        echo json_encode($result);
        exit();
	}

	/**
	 * 对应路由 /user/vaildateCellPhone/{cellPhone}
	 * 验证手机号是否重复,通过GET传参
	 * @param string cellPhone 手机号,手机为11位
	 * @return array('result'=>bool true | false,
	 * 				 'errorCode'=>(暂时全设置为0,后续写配置文件),
	 * 				 'reason'=>对应errorCode的错误原因,现在暂时为手写,
	 * 				 'data'=>array())
	 */
	public function validateCellPhone($cellPhone){

		print_r('user/validateCellPhone');
		print_r('cellPhone:'.$cellPhone);
		exit();

		$result = array (
            'result' => false,
            'errorCode' => 0,
            'reason' => NULL,
            'data' => array()
        );
        //数据传参校验 -- 开始
        //数据传参校验 -- 结束
        //调用 -- 开始

		//调用 -- 结束
        echo json_encode($result);
        exit();
	}

	/**
	 * 对应路由,/user/restPassword/
	 * 找回密码,得到验证码后,重置密码功能,通过POST传参
	 * 
	 * @param string cellPhone 手机号,手机为11位
	 * @param string rePassword 重复密码
	 * @param string password 新密码
	 * @param string code 手机短信验证码
	 * 
	 * @return array('result'=>bool true | false,
	 * 				 'errorCode'=>(暂时全设置为0,后续写配置文件),
	 * 				 'reason'=>对应errorCode的错误原因,现在暂时为手写,
	 * 				 'data'=>array())
	 */
	public function restPassword(){

		print_r('user/restPassword');
		print_r($_POST);
		exit();

		$result = array (
            'result' => false,
            'errorCode' => 0,
            'reason' => NULL,
            'data' => array()
        );

        //数据传参校验 -- 开始
        //数据传参校验 -- 结束
        //领域服务调用 -- 开始

		//领域服务调用 -- 结束
       	echo json_encode($result);
        exit();
	}

	//短信验证码发送接口,存入session,后续获取判断
	//登陆页页面图片验证码接口
	//上传头像接口

}