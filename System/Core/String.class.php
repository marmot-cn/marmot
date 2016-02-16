<?php
namespace System\Class;

class String{
	/**
	 * 全局验证类
	 * 
	 * 'int'		=> '整数',
	 * 'email'		=> '电子邮件',
	 * 'arr'		=> '数组',
	 * 'url'		=> 'URL地址',
	 * 'domain'		=> '域名',
	 * 'pint'		=> '正数',
	 * 'cellphone'  => '手机号码',
	 * 'str'		=> '字符串',
	 *
	 * @param $range 为可选填参数
	 * case 'int' : $range = array(min,max)
	 * case 'pint' : $range = array(max)
	 * case 'str' : $range = array(string_length)
	 * 
	 * 
	 * @example
	 * 1.验证$num是否为整数 string::checkStr($num,'int')
	 * 2.验证$num是否为>2的整数,string::checkStr($num,'int',array(2))
	 * 3.验证$num是否为>2并且<5的整数,string::checkStr($num,'int',array(2,5))
	 * 
	 * 4.验证$num是否为一个正整数,一般我们用在id验证上面,string::checkStr($num,'pint')
	 * 5.验证$num是否为一个正整数且<5,一般我们用在id验证上面,string::checkStr($num,'pint',array(5))
	 * 
	 * 6.验证$str是否为一个string,string::checkStr($str,'str')
	 * 7.验证$str长度是否超过限制即字数<5,string::checkStr($str,'str',array(5))
	 * 
	 * 8.验证$email是否为邮箱格式,string::checkStr($email,'email')
	 * 9.验证$phone是否为手机号码,string::checkStr($phone,'cellphone')
	 * @author chloroplast1983
	 * @version 1.0.20131122
	 */
	public static function checkStr($string, $type = '', $range = array())
	{
		switch ($type)
		{
			case 'int':
				if(!empty($range) && is_array($range) && isset($range[0])){
					$options['min_range']=$range[0];
					if(isset($range[1])){
						$options['max_range']=$range[1];
					}
				}
				if(filter_var($string, FILTER_VALIDATE_INT, array("options" => $options))===false){
					return false;
				}else{
					return true;
				}
				break;
			case 'str':
				if(!empty($range) && is_array($range) && isset($range[0])){
					return mb_strlen($string,'UTF-8') > $range[0] ? false : true;
				}else{
					return is_string($string) && !empty($string);
				}
				break;
			case 'pint':
				$options = array('min_range'=>1);
				if(!empty($range) && is_array($range) && isset($range[0])){
					$options['max_range']=$range[0];
				}
				if(filter_var($string, FILTER_VALIDATE_INT, array("options" => $options))===false){
					return false;
				}else{
					return true;
				}
				break;
			case 'email':
			case 'mail':
				if(!filter_var($string, FILTER_VALIDATE_EMAIL)){
					return false;
				}else{
					return true;
				}
			case 'regexp':
				$options = array("regexp"=>$range['regexp']) ;
				if(!filter_var($string, FILTER_VALIDATE_REGEXP,array("options" => $options))){
					return false;
				}else{
					return true;
				}
				break;
			case 'cellphone':
				if(!filter_var($string, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>'/^(13[0-9]|15[0-9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/')))){
					return false;
				}else{
					return true;
				}
				break;
			case 'arr':
			case 'array':
				if (is_array($string) && !empty($string)){
					return true;
				}else{
					return false;
				}
				break;
			case 'url':
				if(!filter_var($string, FILTER_VALIDATE_URL)){
					return false;
				}else{
					return true;
				}
				break;
			case 'domain':
				if (preg_match("/^(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?$/",$string))
				{
					return true;
				}
				break;
			default:
				return strexists($string, $type);
		}
	}
	
	//获取过滤后的结果
	public function getStr($string, $length, $in_slashes=0, $out_slashes=0, $censor=0, $bbcode=0, $html=0)
	{
		$string = trim($string);
		//传入的字符有slashes
		if($in_slashes)
		{
			$string = string::stripslashesPlus($string);
		}
		if($html < 0) {
			//去掉html标签
			$string = string::htmlFilter($string, 1);
		} elseif ($html == 0) {
			//转换html标签
			$string = string::htmlFilter($string);
		}
		if($censor) {
			//词语屏蔽
			$string = string::censorString($string);
		}
		$string = string::cutStringLength($string);
		//UBB解析
		if($bbcode) {
			$string = string::bbcode($string, $bbcode);
		}
		//输出字符过滤
		if($out_slashes) {
			$string = string::addslashesPlus($string);
		}
		return trim($string);
	}
	
	//UBB解析
	public static function bbcode($string, $bbcode)
	{
		$obj = new Ubb();
		$obj->setString($string);
		return $obj->parse();
	}
	
	//词语过滤功能
	public static function censorString($string)
	{
		global $_FWGLOBAL,$_FWCACHE;
		if (!isset($_FWGLOBAL['censor']))
		{
			$_FWGLOBAL['censor'] = string::getCacheCensorArr();
		}
		if($_FWGLOBAL['censor']['banned'] && preg_match($_FWGLOBAL['censor']['banned'], $string)) {
			showmessage('information_contains_the_shielding_text');
		} else {
			$string = empty($_FWGLOBAL['censor']['filter']) ? $string :
				@preg_replace($_FWGLOBAL['censor']['filter']['find'], $_FWGLOBAL['censor']['filter']['replace'], $string);
		}
		return $string;
	}
	
	//更新词语屏蔽并缓存
	private static function getCacheCensorArr($arr)
	{
		global $_FWGLOBAL, $_FWCACHE;
	
		if (!$_FWCACHE->readCache('censor','system'))
		{
			$_FWGLOBAL['censor'] = $banned = $banwords = array();
			$censorarr = data_get('censor');
			foreach($censorarr['find'] as $key => $censor)
			{
				$censor = string::trimPlus($censor);
				$find = $censorarr['find'][$key];
				$replace = $censorarr['replace'][$key];
				$findword = $find;
				$find = preg_replace("/\\\{(\d+)\\\}/", ".{0,\\1}", preg_quote($find, '/'));
				switch($replace)
				{
					case '{BANNED}':
						$banwords[] = preg_replace("/\\\{(\d+)\\\}/", "*", preg_quote($findword, '/'));
						$banned[] = $find;
						break;
					default:
						$_FWGLOBAL['censor']['filter']['find'][] = '/'.$find.'/i';
						$_FWGLOBAL['censor']['filter']['replace'][] = $replace;
						break;
				}
			}
			if($banned) {
				$_FWGLOBAL['censor']['banned'] = '/('.implode('|', $banned).')/i';
				$_FWGLOBAL['censor']['banword'] = implode(', ', $banwords);
			}
			$_FWCACHE->createCache($_FWGLOBAL['censor']);
		}
		$_FWGLOBAL['censor'] = $_FWCACHE->getCache('censor');
		return $_FWGLOBAL['censor'];
	}
	
	//截取字符串
	public static function cutStringLength($string, $length = 0)
	{
		if($length && strlen($string) > $length)
		{
			//截断字符
			$wordscut = '';
			if(strtolower($_SC['charset']) == 'utf-8') {
				//utf8编码
				$n = 0;
				$tn = 0;
				$noc = 0;
				while ($n < strlen($string)) {
					$t = ord($string[$n]);
					if($t == 9 || $t == 10 || (32 <= $t && $t <= 126))
					{
						$tn = 1;
						$n++;
						$noc++;
					} elseif(194 <= $t && $t <= 223) {
						$tn = 2;
						$n += 2;
						$noc += 2;
					} elseif(224 <= $t && $t < 239) {
						$tn = 3;
						$n += 3;
						$noc += 2;
					} elseif(240 <= $t && $t <= 247) {
						$tn = 4;
						$n += 4;
						$noc += 2;
					} elseif(248 <= $t && $t <= 251) {
						$tn = 5;
						$n += 5;
						$noc += 2;
					} elseif($t == 252 || $t == 253) {
						$tn = 6;
						$n += 6;
						$noc += 2;
					} else {
						$n++;
					}
					if ($noc >= $length)
					{
						break;
					}
				}
				if ($noc > $length) {
					$n -= $tn;
				}
				$wordscut = substr($string, 0, $n);
			} else {
				for($i = 0; $i < $length - 1; $i++)
				{
					if(ord($string[$i]) > 127)
					{
						$wordscut .= $string[$i].$string[$i + 1];
						$i++;
					} else {
						$wordscut .= $string[$i];
					}
				}
			}
			$string = $wordscut;
		}
		return $string;
	}
	
	//添加转义字符（Array加强版）
	public static function addslashesPlus($string)
	{
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = string::addslashesPlus($val);
			}
		} else {
			$string = addslashes($string);
		}
		return $string;
	}
	
	//trim加强版
	public static function trimPlus($string)
	{
		if (is_array($string))
		{
			foreach($string as $key => $val) {
				$string[$key] = string::trimPlus($val);
			}
		} else {
			$string = trim($string);
		}
		return $string;
	}
	
	//代码转义（Array加强版）
	public static function stripslashesPlus($string){
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = string::stripslashesPlus($val);
			}
		} else {
			$string = stripslashes($string);
		}
		return $string;
	}
	
	//取消HTML代码（Array加强版）
	static function htmlspecialcharsPlus($string){
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = string::htmlspecialcharsPlus($val);
			}
		} else {
			$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
				str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
		}
		return $string;
	}

	public static function dhtmlspecialchars($string, $flags = false) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = string::dhtmlspecialchars($val, $flags);
			}
		} else {
			if(!$flags) {
				$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
				if(strpos($string, '&amp;#') !== false) {
					$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
				}
			} else {
				$string = htmlspecialchars($string, $flags);
			}
		}
		return $string;		
	}
	
	public static function strip_tags_attributes($string, $allowtags = null, $allowattributes = null) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = string::strip_tags_attributes($val, $allowtags,$allowattributes);
			}
		} else {
			$string = strip_tags_attributes($string, $allowtags, $allowattributes);
		}
		return $string;
	}
	//html过滤器（去掉，转义，部分）
	public static function htmlFilter($html, $remove = false, $replace = true, $allow = true)
	{
		if ($remove){
			$html = preg_replace("/(\<[^\<]*\>|\r|\n|\s|\[.+?\])/is", ' ', $html);
		}
		if ($replace){
			$html = string::stripslashesPlus($html);
		}
		if ($allow){
			$html = string::trimPlus($html);
// 			$html = string::stripslashesPlus($html);

			$allowtags = '<b><i><u><blockquote><img><strong><em><font><p><h1><h2><h3><h4><h5><h6><strike><span><br><table><tbody><th><tr><td><caption><colgroup><div>';
			$allowattributes = 'target,src,width,height,alt,title,size,face,color,align,style,class,rel,rev';
			$html = string::strip_tags_attributes($html,$allowtags,$allowattributes);
			
			$html = string::dhtmlspecialchars($html);
			$html = string::addslashesPlus($html);
		}
		return $html;
	}
	

	
	//获取数字，强制转换数字，类似intval
	public static function parseInt($string){
		if(preg_match('/(\d+)/', $string, $array)) {
			return $array[1];
		} else {
			return 0;
		}
	}
	
	//通用转码
	public function safeEncoding($string,$outEncoding = 'UTF-8') {  
		$encoding = "UTF-8";  
		for($i=0;$i<strlen($string);$i++)  
		{  
			if(ord($string{$i})<128)  
			{
				continue;  
			}
			
			if((ord($string{$i})&224)==224)  
			{  
				//第一个字节判断通过  
				$char = $string{++$i};  
				if((ord($char)&128)==128)  
				{  
					//第二个字节判断通过  
					$char = $string{++$i};  
					if((ord($char)&128)==128)  
					{  
						$encoding = "UTF-8";  
						break;  
					}  
				}  
			}  
			if((ord($string{$i})&192)==192)  
			{  
				//第一个字节判断通过  
				$char = $string{++$i};  
				if((ord($char)&128)==128)  
				{  
					//第二个字节判断通过  
					$encoding = "GB2312";  
					break;  
				}  
			}  
		}  
		
		if(strtoupper($encoding) == strtoupper($outEncoding))  
		{
			return $string;  
		}else{
			return iconv($encoding,$outEncoding,$string);
		}
	}
	
	public static function intvalPlus($mixvar,$base=0)
	{
		if (is_array($mixvar))
		{
			foreach($mixvar as $key => $val) {
				$mixvar[$key] = string::intvalPlus($val,$base);
			}
		} else {
			$mixvar = intval($mixvar,$base);
		}
		return $mixvar;
	}
}