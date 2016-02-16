<?php
//powered by phpcore.net
if(!defined('IN_PHP')) {
	exit('Access Denied');
}

class Form{
	
	public $formHash	= '';				//表单验证字符formHash
	public $method		= 'POST';			//表单提交模式POST、GET
	static $typeArr		= array(			//表单验证支持类型
							'en'		=> '纯英文',
							'cn'		=> '纯中文',
							'num'		=> '纯数字',
							'code'		=> '纯符号',
							'email'		=> '电子邮件',
							'arr'		=> '数组',
							'url'		=> 'URL地址',
							'domain'	=> '域名'
							);
	
	public function __construct(){
		$this->formHash = $this->formhash();
	}
	
	//验证输入字段
	public function check_input($input, $inputType = 'en', $checkType = 'or'){
		if (strexists($inputType,','))
		{
			$type = $this->explodeTypes($inputType);
			//检验多个
			foreach ($type as $key => $value)
			{
				if (isset($this->typeArr[$value]))
				{
					if ($checkType == 'or' && string::checkStr($input, $value))
					{
						return true;
					}elseif ($checkType == 'and' && !string::checkStr($input, $value)){
						return false;
					}
				}elseif ($checkType == 'or' && strexists($input,$value)){
					return true;
				}elseif ($checkType == 'and' && !strexists($input,$value)) {
					return false;
				}
			}
			if ($checkType == 'or')
			{
				return true;
			}else{
				return false;
			}
		}else{
			//检验单个
			return string::checkStr($input, $value);
		}
	}
	
	//获取过滤后的结果
	public function getStr($string, $length, $in_slashes=0, $out_slashes=0, $censor=0, $bbcode=0, $html=0)
	{
		return string::getStr($string, $length, $in_slashes, $out_slashes, $censor, $bbcode, $html);
	}
	
	//获取多个type
	private function explodeTypes($type){
		$type = explode(',',$type);
		foreach ($type as $key => $value)
		{
			if (empty($value))
			{
				unset($type[$key]);
			}
		}
		return $type;
	}
	
	//表单验证码formHash算法
	public static function formhash(){
		global $_FWGLOBAL;
		if(empty($_FWGLOBAL['formhash'])){
			$_FWGLOBAL['formhash'] = substr(md5(substr($_FWGLOBAL['timestamp'], 0, - 7).'
									'.$_FWGLOBAL['pcore_uid'].'
									'.md5(FW_CODE)), 8, 8);
		}
		return $_FWGLOBAL['formhash'];
	}
	//生成hash
	public static function generateHash($var){
		global $_FWGLOBAL;
		$hash = md5(rand(1, 1000). substr($_FWGLOBAL['timestamp'], 4, 4));
		$_SESSION[$var.'Hash'][$hash] = 1;
		return $hash;
	}
	//防止重复性提交
	public static function checkHash($var){
		global $_FWGLOBAL;

		if(empty($_SESSION[$var.'Hash']) || empty($_FWGLOBAL['gp_'.$var.'Hash']) || $_SESSION[$var.'Hash'][$_FWGLOBAL['gp_'.$var.'Hash']] != 1){
			return false;
		}
		unset($_SESSION[$var.'Hash'][$_FWGLOBAL['gp_'.$var.'Hash']]);
		return true;
	}
	//判断提交正确性
	public static function submitcheck($var){
		if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST'){
			
			if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $_POST['formhash'] == form::formhash()) {
				return true;
			} else {
				showmessage('信息提交','信息提交超时',make_url());
			}
		} else {
			return false;
		}
	}
	
	//判断提交正确性 -- 开始
	public static function vaidatePost(){
		if(empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])){
			return true;
		}else{
			return false;
		}
	}
	//判断提交正确性 -- 结束
}

?>