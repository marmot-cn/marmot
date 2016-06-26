<?php
namespace Common\Service;
use Common\Model\Message;
/**
 * 通用信息短信角色
 * 
 * @author chloroplast
 * @version 1.0:20160223
 */
class RestPasswordSmsService implements SmsInterface,VerifyCodeInterface{

	/**
	 * @var Message $message 发送手机短信
	 */
	private $message;

	/**
	 * @var integer $code 验证码
	 */
	private $code;

	/**
	 * @var string $key session中key的前缀
	 */
	private $key = 'restPasswordSession';

	/**
	 * 生成验证码存到$_SESSION中
	 */
	private function generate(){

		$len = 6;
		$chars = '0123456789';
		// characters to build the password from
		mt_srand((double)microtime() * 1000000 * getmypid());
		// seed the random number generater (must be done)
		$code = '';
		while (strlen ( $string ) < $len){
			$code .= substr ( $chars, (mt_rand () % strlen ( $chars )), 1 );
		}
		//存入session
		$_SESSION['restPasswordSession'] = $code;

		$this->code = $code;
	}
	
	/**
	 * 发送验证码
	 */
	public function send(string $cellPhone){

		//生成验证码
		$this->generate();
		//组合拼接最终message
		$this->message->setTitle(sprintf(SMS_REGISTER_MESSAGE,$this->code));
		$this->message->setTargets($cellPhone);
		//调用Get接口发送
		// $client = new GuzzleHttp\Client();
		// $response = $client->request('GET', 'xxx');
		// $body = $response->getBody();

		// return $body->getContents();
		return $this->message;
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