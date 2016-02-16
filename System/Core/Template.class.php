<?php
//powered by phpcore.net
if(!defined('IN_PHP')) {
	exit('Access Denied');
}

class template{
	
	private $path;			//模板路径
	private $cachePath;		//模板缓存路径
	private $name;			//模板名
	private $cacheName;		//模板缓存名
	private $localHead;
	
	private $replaceArr;	//替换列队
	public $dataArr;		//方便以后调用的数据

	private $module;		//模块name
	private $action;		//模块动作
	
	public function __construct(){
		global $_MOD;
		$this->path			= '';
		$this->cachePath	= '';
		$this->name			= '';
		$this->cacheName	= '';
		$this->localHead 	= '';
		$this->replaceArr	= array();
		$this->dataArr		= array();
	
		$this->module		= isset($_MOD)?$_MOD->getField('enName'):'system';
		$this->action		= '';
	}
	
	public function setModule($moduleName){
		$this->module = module::module_getname($moduleName);
	}
	
	public function html_OUT($name){
		$this->setName($name);
		$this->setPath();
		if ($this->parse()){
			return $this->cachePath.$this->cacheName;
		}else{
			return false;
		}
	}
	public function renderFile($name,$renderData,$moduleName){
		empty($moduleName) ? '' : $this->setModule($moduleName);
		
		$this->setName($name);
		$this->setPath();
		if ($this->parse()){
			ob_start();
			include $this->cachePath.$this->cacheName;
			return ob_get_clean();
		}else{
			return false;
		}
	}
	public function setLocalHead($localHead){
		$this -> localHead = $localHead;
	}
	public function getLocalHead(){
// 		return self::readtemplate($this -> localHead);
		return self::html_OUT($this -> localHead);
	}
	//xml输出
	public function xml_OUT($content = '', $root = 'root'){
		global $_FWC;
		$content = empty($content)?ob_get_contents():$content;
		@header("Expires: -1");
		@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");
		@header("Content-type: application/xml; charset=$_FWC[charset]");
		echo '<'."?xml version=\"1.0\" encoding=\"$_FWC[charset]\"?>\n";
		$root = empty($root)?'root':$root;
		if (!is_array($content))
		{
			echo '<'.$root.'><![CDATA['.trim($content).']]></'.$root.'>';
		}else{
			echo template::array2xml($content,$root);
		}
		exit();
	}
	
	//json格式输出
	public static function json_OUT($content = '', $callback = ''){
		global $_FWC,$_FWGLOBAL;
		
		$content = empty($content)?ob_get_contents():$content;
		@header("Expires: -1");
		@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");
		@header("Content-type: application/json; charset=$_FWC[charset]");
		if (!is_array($content))
		{
			$content=array('html'=>$content);
		}
		echo empty($callback)?json_encode($content):$callback.'('.json_encode($content).');';
		// DBW::close();
		// DBR::close();
		exit();
	}
	
	public function setNewTag($tagName, $funcName){
		if (function_exists($funcName)){
			$this->replaceArr[$tagName] = $funcName;
			return true;
		}else{
			return false;
		}
	}
	
	private function setName($name){
		if (strexists($name,'/')){
			$this->action = $name;
			$this->name = $name.'.tpl.php';
			$cacheName = str_replace('/','_',$name);
			$this->cacheName = $cacheName.'.cache.php';
		}else{
			$this->action = $name;
			$this->name = $name.'.tpl.php';
			$this->cacheName = 'MOD_'.$this->module.'_'.$name.'.cache.php';
		}
	}
	
	private function setPath(){
		global $_FWCONFIG;
		$this->path	=module::module_getroot($this->module).'template'.DIRECTORY_SEPARATOR;
//		$this->path	= ($this->module=='system')?G_ROOT.'template'.DIRECTORY_SEPARATOR.$_FWCONFIG['template'].DIRECTORY_SEPARATOR
//					:module::module_getroot($this->module).'template'.DIRECTORY_SEPARATOR;
		$this->cachePath = G_ROOT.'cache'.DIRECTORY_SEPARATOR.'tpl_cache'.DIRECTORY_SEPARATOR;
	}
	
	private function parse(){
		if (!D_BUG && file_exists($this->cachePath.$this->cacheName)){
			return true;
		}else{
			if (file_exists($this->path.$this->name)){
				$template = sreadfile($this->path.$this->name);
			}elseif (file_exists(S_ROOT.$this->name)){
				$template = sreadfile(S_ROOT.$this->name);
			}elseif (file_exists(G_ROOT.'template'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.$this->name)){
				$template = sreadfile(G_ROOT.'template'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.$this->name);
			}else {
				return false;
			}
			$template = $this->parse_subtemp($template);
			$template = $this->parse_var($template);
			$template = "<?php if(!defined('IN_PHP')) exit('Access Denied');?>$template<?php template::ob_out();?>";
			$template = str_replace('<?exit?>', '', $template);
			//write
			if(!swritefile($this->cachePath.$this->cacheName, $template)) {
				exit("File: $this->cachePath.$this->cacheName can not be write!");
			}else{
				return true;
			}
		}
	}
	
	private function parse_subtemp($template){
		//兼容子模板标签（三层）
		for($i = 1; $i <= 3; $i++) {
			if(strexists($template, '{template')) {
				$template = preg_replace("/\<\!\-\-\{template\s+([a-z0-9_\/]+)\}\-\-\>/ie", "template::readtemplate('\\1')", $template);
			}
		}
		//$template = preg_replace("/\<\!\-\-\{template::localHeader\}\-\-\>/ie", "template::readtemplate('$this->localHead')", $template);
		$template = preg_replace("/\<\!\-\-\{eval\s+(.+?)\s*\}\-\-\>/ies", "template::evaltags('\\1')", $template);
		$this->replaceArr['date'] = 'sgmdate';	//时间处理
		$this->replaceArr['avatar'] = 'avatar';	//头像处理
		$this->replaceArr['formhash'] = 'formhash'; //表单处理
		$this->replaceArr['template'] = 'readTemplate'; //跨模块调用模板
		$template = $this->parse_tags($template);
		return $template;
	}
	
	private function parse_var($template){
		//注释修改
		$template = preg_replace ( "/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template );
		
		//20130708 修改,添加匹配 include 规则:服务于 localhead -- start
		$template = preg_replace ( "/[\n\r\t]*\{include\s+(.+?)\}[\n\r\t]*/ies", "\$this->stripvtags('<?php include_once \\1; ?>')", $template );
		//20130708 修改,添加匹配 include 规则:服务于 localhead -- end
		$template = preg_replace ( "/[\n\r\t]*\{echo\s+(.+?)\}[\n\r\t]*/ies", "\$this->stripvtags('<?php echo \\1; ?>')", $template );
		
		$template = preg_replace ( "/([\n\r\t]*)\{if\s+(.+?)\}([\n\r\t]*)/ies", "\$this->stripvtags('\\1<?php if(\\2) { ?>\\3')", $template );
		$template = preg_replace ( "/([\n\r\t]*)\{elseif\s+(.+?)\}([\n\r\t]*)/ies", "\$this->stripvtags('\\1<?php } elseif(\\2) { ?>\\3')", $template );
		$template = preg_replace ( "/\{else\}/i", "<?php } else { ?>", $template );
		$template = preg_replace ( "/\{\/if\}/i", "<?php } ?>", $template );
		
		$template = preg_replace ( "/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\}[\n\r\t]*/ies", "\$this->stripvtags('<?php if(is_array(\\1)) foreach(\\1 as \\2) { ?>')", $template );
		$template = preg_replace ( "/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*/ies", "\$this->stripvtags('<?php if(is_array(\\1)) foreach(\\1 as \\2 => \\3) { ?>')", $template );
		$template = preg_replace ( "/\{\/loop\}/i", "<?php } ?>", $template );
		
		$template = preg_replace ( "/[\n\r\t]*\{widget\s(.+?)\s+(.+?)\s+(.+?)\}[\n\r\t]*/ies", "\$this->stripvtags('<?php echo widgetFactory::widget(\\1 , \\2 , \\3)?>')", $template );//20131129支持widget
		//变量修改
		$template = preg_replace ( "/(\\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/s", "\\1['\\2']", $template );
		$template = preg_replace ( "/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?php echo \\1;?>", $template );
		
		$template = preg_replace ( "/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>", $template );
		return $template;
	}
	
	private function parse_tags($template){
		foreach ($this->replaceArr as $key => $value)
		{
			if (function_exists($value))
			{
				$template = preg_replace("/\<\!\-\-\{$key\((.*?)\)\}\-\-\>/ie", "template::addtags('$value','\\1')", $template);
			}
		}
		return $template;
	}
	static function addtags($func,$var){
		return '<?php echo '.$func.'('.$var.'); ?>';
	}
	static function addquote($var){
		return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
	}
	static function stripvtags($expr, $statement=''){
		$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
		$statement = str_replace("\\\"", "\"", $statement);
		return $expr.$statement;
	}
	static function evaltags($php){
		return "<?php ".template::stripvtags($php)." ?>";
	}
	public function readtemplate($name){
		global $_FWGLOBAL, $_FWCONFIG;
		$tpl = strexists($name,'/')?S_ROOT.$name:G_ROOT.'template'.DIRECTORY_SEPARATOR.$_FWCONFIG['template'].DIRECTORY_SEPARATOR.$name;
		$file = $tpl.'.tpl.php';
		if(!file_exists($file)) {
			global $_MOD;
//			if (!isset($_MOD) || $_MOD->getField('enName') == 'system')
			if (!isset($_MOD)){
				$tpl = str_replace($_FWCONFIG['template'].DIRECTORY_SEPARATOR, 'default'.DIRECTORY_SEPARATOR, $tpl);
			}else{
				$tpl = str_replace(G_ROOT.'template'.DIRECTORY_SEPARATOR.$_FWCONFIG['template'].DIRECTORY_SEPARATOR,
				module::module_getroot($_MOD->getField('enName')).'template'.DIRECTORY_SEPARATOR, $tpl);
			}
			$file = $tpl.'.tpl.php';
		}
		$content = sreadfile($file);
		return $content;
	}
	static function ob_out(){
		global $_FWGLOBAL, $_FWCONFIG, $_FWC;
	
		$content = ob_get_contents();
	
		$preg_searchs = $preg_replaces = $str_searchs = $str_replaces = array();
	
		if($_FWCONFIG['linkguide'])
		{
			//外链开启
			$preg_searchs[] = "/\<a href\=\"http\:\/\/(.+?)\"/ie";
			$preg_replaces[] = 'template::iframe_url(\'\\1\')';
		}
		if($_FWCONFIG['allowrewrite'])
		{
			//debug伪静态开启
			$preg_searchs[] = "/\<a href\=\"(index\.php\?|\.\/\?|\?|\/?)(mod\=([a-z0-9\=]+?)(\&|\")|ac\=([a-z0-9\=]+?)(\&|\")|op\=([a-z0-9\=]+?)(\&|\"))+(([a-z0-9=&#]*)\"||\s)/ie";
			$preg_searchs[] = "/\<a href\=\"index.php\"/i";
			$preg_searchs[] = "/\<a href\=\"file.php\"/i";
	
			$preg_replaces[] = 'template::rewrite_url(\'\\3\',\'\\5\',\'\\7\',\'\\10\')';
			$preg_replaces[] = '<a href="index.html"';
			$preg_replaces[] = '<a href="file.html"';
		}
	
		if($preg_searchs) {
			$content = preg_replace($preg_searchs, $preg_replaces, $content);
		}
		if($str_searchs) {
			$content = trim(str_replace($str_searchs, $str_replaces, $content));
		}
		obclean();
		if($_FWCONFIG['headercharset']) {
			@header('Content-Type: text/html; charset='.$_FWC['charset']);
		}
		echo $content;
		if(D_BUG) {
			//嵌入debug部分
		}
	}
	static function rewrite_url($mod, $ac, $op, $para){
		global $_FWC;
		$para = str_replace(array('&','='), array('/', '/'), $para);
		$mod = empty($mod)?$_FWC['default_mod']:$mod;
		$ac = empty($ac)?'index':$ac;
		$op = empty($op)?'index':$op;
		return '<a href="'.$_FWC['siteurl'].$mod.'/'.$ac.'/'.$op.'/'.(empty($para)?'index':$para).'.html"';
	}
	static function iframe_url($url){
		return '<a href="index.php?url=http://'.rawurlencode($url).'"';
	}
	static function array2xml($array, $root){
		if (is_array($array))
		{
			$returnStr = '<'.$root.'>';
			foreach ($array as $key => $value)
			{
				$key = is_numeric($key)?'item'.$key:$key;
				$returnStr.= '<'.$key.'>'.template::array2xml($value).'</'.$key.'>'."\n";
			}
			return $returnStr.'</'.$root.'>';
		}elseif (is_object($array)){
			//debug 数据类型错误
			showmessage('数据类型错误');
			return false;
		}else{
			return (strexists($array,'<') || strexists($array,'>'))?"<![CDATA[".trim($array)."]]>":trim($array);
		}
	}
	static function multi($num, $perpage, $curpage, $mpurl){
		global $_FWCONFIG;
		$page = 5;
		$multipage = array();
// 		$mpurl .= strexists($mpurl, '?') ? '&' : '?';
		$mpurl .= '-';
		$realpages = 1;
		if($num > $perpage) {
			$offset = 2;		
			$realpages = @ceil($num / $perpage);
			$pages = $_FWCONFIG['maxpage'] && $_FWCONFIG['maxpage'] < $realpages ? $_FWCONFIG['maxpage'] : $realpages;
			if($page > $pages) {
				$from = 1;
				$to = $pages;
			} else {
				$from = $curpage - $offset;
				$to = $from + $page - 1;
				if($from < 1) {
					$to = $curpage + 1 - $from;
					$from = 1;
					if($to - $from < $page) {
						$to = $page;
					}
				} elseif($to > $pages) {
					$from = $pages - $page + 1;
					$to = $pages;
				}
			}
			$n=1;
			if ($curpage - $offset > 1 && $pages > $page){
				$multipage[$n] = array('url'=>$mpurl.'page=1','html'=>'1 ...');
				$n++;
			}
			if ($curpage > 1){
				$multipage[$n] = array('url'=>$mpurl.'page-'.($curpage - 1),'html'=>'&lsaquo;&lsaquo;');
				$n++;
			}
			for($i = $from; $i <= $to; $i++) {
				$multipage[$n] = ($i == $curpage) ? array('url'=>'#','html'=>$i,'class'=>' class="active"') :
					array('url'=>$mpurl.'page-'.$i,'html'=>$i);
				$n++;
			}
			if ($curpage < $pages){
				$multipage[$n] = array('url'=>$mpurl.'page-'.($curpage + 1),'html'=>'&rsaquo;&rsaquo;');
				$n++;
			}
			if ($to < $pages){
				$multipage[$n] = array('url'=>$mpurl.'page-'.$pages,'html'=>'... '.$realpages);
				$n++;
			}
			$multipage[0] = empty($multipage) ? array():array('url'=>'','html'=>'<a>共 '.$pages.' 页</a> ');
		}
		
		ksort($multipage);
		return $multipage;
	}
}

?>