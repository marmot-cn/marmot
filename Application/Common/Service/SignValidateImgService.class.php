<?php
namespace Common\Service;
use Common\Model\Message;
use Gregwar\Captcha\CaptchaBuilder;

/**
 * 登录验证图片角色,这里使用了第三方的类
 * 
 * @author chloroplast
 * @version 1.0.20160223
 */
class SignValidateImgService implements VerifyCodeInterface{

	/**
	 * @var integer $code 验证码
	 */
	private $code;

	/**
	 * @var string $key session中key的前缀
	 */
	private $key = 'signSession';

	/**
	 * 生成验证码存到$_SESSION中
	 */
	public function generate(){

		$builder = new CaptchaBuilder;
		$builder->build();
		header('Content-type: image/jpeg');
		$builder->output();
		//存入session
		$_SESSION[$this->key] = $builder->getPhrase();
	}
	

	/**
	 * 验证验证码
	 */
	public function verify($code){
		if($code == $_SESSION[$this->key]){
			unset($_SESSION[$this->key]);
			return true;
		}
		return false;
	}
}