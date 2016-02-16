<?php
//powered by phpcore.net
if(!defined('IN_PHP')) {
	exit('Access Denied');
}

class file{
	
	private $fileId = 0; 		//上传文件主键
	private $fileName	= '';		//文件名
	private $fileTemp	= '';		//临时文件地址
	private $fileExt	= '';		//文件扩展名
	private $filePath	= '';		//文件存储路径
	private $fileErr	= '';		//上传错误代码
	private $fileHash	= '';		//文件Hash值
	private $fileSize	= '';		//文件大小（单位G/M/K）
	private $fileThumb	= '';		//文件缩略图（图片文件为缩略图，其他则为文件图标或留空）
	private $fileTime	= '';		//上传文件时间戳
	private $fileOwner	= 'user';	//文件所属（user:个人;unit:组织;System:系统后台）
	private $fileUid	= '';		//文件所属id（和fileOwner字段挂钩）
	
	private $fileData		= array();	//下载文件序列
	
	private $thumbAuto		= false;	//自动增加缩略图
	private $watermarkAuto	= false;	//自动增加水印
	
	private $fileTypeData	= array(	//所有支持的文档
			'av' => array('av', 'avi', 'wmv', 'wav'),
			'real' => array('rm', 'rmvb'),
			'binary' => array('dat'),
			'flash' => array('swf'),
			'html' => array('html', 'htm'),
			'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
			'office' => array('doc', 'xls', 'ppt','docx'),
			'pdf' => array('pdf'),
			'rar' => array('rar', 'zip'),
			'text' => array('txt'),
			'bt' => array('bt'),
			'zip' => array('tar', 'rar', 'zip', 'gz'),
		);
	private $fileTypeAllow	= array(	//允许上传的类型组key（无需转义后缀）
			'av','real','flash','image','office','pdf','rar','text'
		);
	
	//构造函数
	public function __construct($FILES = array(),$setting = array())
	{
		//debug 需要判断setting值
		if (empty($FILES))
		{
			//do nothing
		}elseif (is_array($FILES)){
			$this->upload($FILES);
		}elseif (is_string($FILES) && strlen($FILES) == 32){
			$this->getFileData($FILES);
		}else{
			showmessage('参数错误');
		}
	}
	
	public function uploads($FILES)
	{
		if (is_array($FILES))
		{
			$return = array();
			foreach ($FILES as $key => $value)
			{
				$return[$key] = $this->upload($value);
			}
		}else{
			$return = false;
		}
		return $return;
	}
	
	//一般上传
	public function upload($FILE){
		global $_FWGLOBAL;
		//获取基础信息（大小、临时地址、名字、错误编号）
		$this->fileSize	= $this->getFileSize(intval($FILE['size']));
		$this->fileTemp	= $FILE['tmp_name'];
		$this->fileName	= $FILE['name'];
		$this->fileErr	= $FILE['error'];
		//基础错误判断
		if(!$this->check_fileTemp())return false;
		//获取文件Hash
		$this->fileHash = md5(md5_file($this->fileTemp).$FILE['size']);
		//判断重复文件
// 		$this->checkSaveFile();
		//获取文件后缀
		$this->fileExt = $this->getFileExt();
		//判断后缀是否允许
		$this->check_fileExt();
		//记录文件上传时间
		$this->fileTime = $_FWGLOBAL['timestamp'];
		//记录文件所有权类型
		if (!$this->setFileOwner()){
			$this->fileOwner = 'user';
			$this->fileUid = 0;
		}
		//确定文件存储路径(含文件名)
		$this->getPath();
		//上传、移动临时文件部分
		$this->fileMove($this->fileTemp, $this->getPath(), true);
		//记录数据库
		$setArr = $this->saveFileToDB();
		$this -> cacheWriteFile($setArr);
		return $setArr;
	}
	
	//判断是否有重复文件
	private function checkSaveFile(){
		global $_FWCONFIG;
		//判断是否已经有文件
		if ($_FWCONFIG['checkSameFile'])
		{
			$fileData = sqlforlist('system_file','fileHash=\''.$this->fileHash.'\'');
			if (!empty($fileData)){
				$this->fileData[$this->fileHash] = $fileData[0];
				return false;
			}
		}
		//判断文件重复
		if (isset($this->fileData[$this->fileHash]))
		{
			return $this->fileData[$this->fileHash];
		}
	}
	/**
	 * 注册文件
	 */
	public function registerFile($fileHash,$fileName,$fileExt,$filePath,$fileSize,$fileUid){
		global $_FWGLOBAL;
		$this->fileHash = $fileHash;
		$this->fileName = $fileName;
		$this->filePath = $filePath;
		$this->fileExt = $fileExt;
		$this->fileSize = file::formatsize($fileSize);
		$this->fileOwner = 'user';
		$this->fileUid = $fileUid;
		$this -> fileTime = $_FWGLOBAL['timestamp'];
		
		
		$setArr = $this->saveFileToDB();
		$this -> cacheWriteFile($setArr);
		return $setArr;
	}
	//记录数据库
	private function saveFileToDB(){
		global $_FWGLOBAL;
		$setArr = array(
						'fileHash'	=> $this->fileHash,
						'fileName'	=> $this->fileName,
						'fileExt'	=> $this->fileExt,
						'filePath'	=> $this->filePath,
						'fileSize'	=> $this->fileSize,
						'fileTime'	=> $this->fileTime,
						'fileOwner'	=> $this->fileOwner,
						'fileUid'	=> $this->fileUid
						);
		$setArr['fileId'] = systemFileModel::insert(saddslashes($setArr));
// 		$fileId = $_FWGLOBAL['db']->inserttable('system_file',saddslashes($setArr),1);
// 		$setArr['fileId'] = $fileId;
		return $setArr;
	}
	
	//缓存写文件　
	private function cacheWriteFile($setArr){
		systemFileMemcache::add($setArr['fileId'], $setArr);
	}
	//缓存读文件
	private function cacheReadFile($fileId){
		return systemFileMemcache::get($fileId);
	}
	//基础错误判断
	private function check_fileTemp()
	{
		if(empty($this->fileSize))
		{
			return false;
			showmessage('无法获取文件大小');
		}
		if (empty($this->fileTemp))
		{
			return false;
			showmessage('上传到临时文件夹错误');
		}
		if (!empty($this->fileErr))
		{
			return false;
			showmessage('uploadError:'.$this->fileErr);
		}
		return true;
	}
	
	//获取路径
	private function getPath($fileName = '')
	{
		global $_FWCONFIG;
		if ($_FWCONFIG['uploadDirType'] == 'date')
		{
			$name1 = gmdate('Ym');
			$name2 = gmdate('j');
		}elseif ($_FWCONFIG['uploadDirType'] == 'ext'){
			$name1 = $fileExt = isset($fileName)?$this->getFileExt($fileName):$this->fileExt;
			$name2 = gmdate('Ymj');
		}else{
			//默认时间
			$name1 = gmdate('Ym');
			$name2 = gmdate('j');
		}
		//判断第一层文件夹
		$newfilename = $_FWCONFIG['attachDir'].$name1.DIRECTORY_SEPARATOR;
		if(!is_dir($newfilename)) {
			if(!@mkdir($newfilename,0777)) {
				runlog('error', "DIR: $newfilename can not make");
				return false;
			}
		}
		//判断第二层文件夹
		$newfilename .= $name2.DIRECTORY_SEPARATOR;
		if(!is_dir($newfilename)) {
			if(!@mkdir($newfilename,0777)) {
				runlog('error', "DIR: $newfilename can not make");
				return false;
			}
		}
		//路径赋值
		$this->filePath = $name1.'/'.$name2.'/'.$this->fileHash.'.'.$this->fileExt;
		//返回服务器路径
		return $newfilename.$this->fileHash.'.'.$this->fileExt;
	}
	
	//获取后缀
	private function getFileExt($fileName = '')
	{
		$fileName = empty($fileName)?$this->fileName:$fileName;
		return strtolower(substr(strrchr($fileName, '.'), 1, 10));
	}
	//判断后缀是否需要更改
	private function check_fileExt($fileExt = ''){
		
		$fileExt = empty($fileExt)?$this->fileExt:$fileExt;
		foreach ($this->fileTypeData as $key => $value){
			if (in_array($fileExt, $value))
			{
				$return = $key;
				break;
			}
		}
		if (!$return || !in_array($return,$this->fileTypeAllow)){
			$this->fileExt = '_'.$this->fileExt;
		}
		return true;
	}
	//内外地址转换
	public static function getFileURL($filePath)
	{
		global $_FWC, $_FWCONFIG;
		return str_replace(array(
							$_FWCONFIG['attachDir'],
							S_ROOT,
							'\\'
						),array(
							$_FWCONFIG['attachUrl'],
							$_FWC['siteurl'],
							'/'
						),$filePath);
	}
	
	//获取文件大小
	private function getFileSize($fileSize = '')
	{
		$fileSize = empty($fileSize)?$this->fileSize:$fileSize;
		return file::formatsize($fileSize);
	}
	
	//设定文件所属单位
	public function setFileOwner($uid = 0, $owner = ''){
		$this->fileOwner	= ($owner == 'unit')?'unit':'user';
		if ($this->fileOwner == 'user')
		{
			global $_FWGLOBAL;
			$this->fileUid = empty($uid)?$_FWGLOBAL['pcore_uid']:intval($uid);
			if (empty($this->fileUid))
			{
				return false;
			}
		}elseif (empty($uid)){
			return false;
		}else {
			$this->fileUid = intval($uid);
		}
		return true;
	}
	
	//格式化文件大小
	public static function formatsize($size)
	{
		$prec=3;
		$size = round(abs($size));
		$units = array(0=>" B ", 1=>" KB", 2=>" MB", 3=>" GB", 4=>" TB");
		if ($size==0) return str_repeat(" ", $prec)."0$units[0]";
		$unit = min(4, floor(log($size)/log(2)/10));
		$size = $size * pow(2, -10*$unit);
		$digi = $prec - 1 - floor(log($size)/log(10));
		$size = round($size * pow(10, $digi)) * pow(10, -$digi);
		return $size.$units[$unit];
	}
	//移动文件
	public static function fileMove($formFilePath, $toFilePath, $deleteFile = false)
	{
		if(@copy($formFilePath, $toFilePath))
		{
			if ($deleteFile)
			{
				@unlink($formFilePath);
			}
		} elseif((function_exists('move_uploaded_file') && @move_uploaded_file($formFilePath, $toFilePath))) {
		} elseif(@rename($formFilePath, $toFilePath)) {
		} else {
			showmessage('无法复制到指定文件夹。');
		}
	}
	
	//一般下载（获取信息、增加访问次数、判断权限）
	public function download($hash){
		$this->getFileData($hash);
		$this->add_downloadCount($hash);
		return $this->fileData[$hash];
	}
	
	public function getFileData($fileId){
		
		if(empty($fileId)){
			return false;
		}
		$result = $this -> cacheReadFile($fileId);
		if (empty($result)){
			$result	= systemFileModel::select('fileId='.$fileId, '*');
// 			$query = $_FWGLOBAL['db']->query('SELECT * FROM '.tname('file','system').' WHERE fileId='.$fileId);
// 			$result = $_FWGLOBAL['db']->fetch_array($query);
			$result = $result[0];
			$this -> cacheWriteFile($result);
		}
		return $result;
	}
	
	public function getFilesData($fileIds){
		
		if(empty($fileIds)){
			return false;
		}
		$list = array();
		$fids = $comma = '';
		foreach($fileIds as $value){
			$result = $this -> cacheReadFile($value);
			if(!empty($result)){
				$list[$value] = $result;
			}else{
				$fids .= $comma.$value;
				$comma = ',';
			}
		}
		if(!empty($fids)){
			global $_FWGLOBAL;
			$result = array();
// 			$query = $_FWGLOBAL['db']->query('SELECT * FROM '.tname('file','system').' WHERE fileId IN ('.$fids.')');
// 			while ($value = $_FWGLOBAL['db']->fetch_array ($query)){
// 				$result[] = $value;
// 			}
			$result = systemFileModel::select('fileID IN ('.$fids.')', '*');
			if(!empty($result)){
				foreach($result as $value){
					$list[$value['fileId']] = $value;
					$this -> cacheWriteFile($value);
				}
			}
		}
		return $list;
	}
	//添加下载次数
	private function add_downloadCount($hash){
// 		global $_FWGLOBAL, $_FWCOOKIE;
// 		//防止重复添加下载
// 		if (!isset($_FWCOOKIE['down_'.$hash]))
// 		{
// 			$_FWGLOBAL['db']->updatetable('system_file',array('fileDownCount'=>'+1'),array('fileHash'=>$hash));
// 			ssetcookie('down_'.$hash,1,3600);
// 		}
// 		return true;
	}
	
//远程附件还未测试 debug
	
	private $fileRemote	= 0;		//上传模式（0:本地;1:远程）
	
	private $ftpHost	= '';		//远程主机地址
	private $ftpSSL		= '';		//是否支持ssl连接
	private $ftpPort	= '';		//远程FTP端口
	private $ftpUser	= '';		//远程FTP用户名
	private $ftpPass	= '';		//远程FTP密码
	private $ftpPath	= '';		//远程FTP存储路径
	private $ftpTimeout	= '';		//连接超时时间
	
	//FTP上传($source:源文件地址;$dest:目标文件地址)
	private function ftpupload($source, $dest)
	{
		if(!($ftpconnid = file::sftp_connect())) {
			return 0;
		}
		$ftppwd = FALSE;
		$tmp = explode('/', $dest);
		$dest = array_pop($tmp);
	
		foreach ($tmp as $tmpdir) {
			if(!file::sftp_chdir($ftpconnid, $tmpdir)) {
				if(!file::sftp_mkdir($ftpconnid, $tmpdir)) {
					runlog('FTP', "MKDIR '$tmpdir' ERROR.", 0);
					return 0;
				}
				if(!function_exists('ftp_chmod') || !file::sftp_chmod($ftpconnid, 0777, $tmpdir)) {
					file::sftp_site($ftpconnid, "'CHMOD 0777 $tmpdir'");
				}
				if(!file::sftp_chdir($ftpconnid, $tmpdir)) {
					runlog('FTP', "CHDIR '$tmpdir' ERROR.", 0);
					return 0;
				}
				file::sftp_put($ftpconnid, 'index.htm', S_ROOT.'./cache/index.htm', FTP_BINARY);
			}
		}
	
		if(file::sftp_put($ftpconnid, $dest, $source, FTP_BINARY)) {
			if(file_exists($source.'.thumb.jpg')) {
				if(file::sftp_put($ftpconnid, $dest.'.thumb.jpg', $source.'.thumb.jpg', FTP_BINARY)) {
					@unlink($source);
					@unlink($source.'.thumb.jpg');
					file::sftp_close($ftpconnid);
					return 1;
				} else {
					file::sftp_delete($ftpconnid, $dest);
				}
			} else {
				@unlink($source);
				file::sftp_close($ftpconnid);
				return 1;
			}
		}
		runlog('FTP', "Upload '$source' error.", 0);
		return 0;
	}
	
	//FTP连接
	public static function sftp_connect()
	{
		global $_FWGLOBAL;
	
		@set_time_limit(0);
	
		$func = $_FWGLOBAL['setting']['ftpssl'] && function_exists('ftp_ssl_connect') ? 'ftp_ssl_connect' : 'ftp_connect';
		if($func == 'ftp_connect' && !function_exists('ftp_connect')) {
			runlog('FTP', "FTP NOT SUPPORTED.", 0);
		}
		if($ftpconnid = @$func($_FWGLOBAL['setting']['ftphost'], $_FWGLOBAL['setting']['ftpport'], 20)) {
			if($_FWGLOBAL['setting']['timeout'] && function_exists('ftp_set_option')) {
				@ftp_set_option($ftpconnid, FTP_TIMEOUT_SEC, $_FWGLOBAL['setting']['timeout']);
			}
			if(file::sftp_login($ftpconnid, $_FWGLOBAL['setting']['ftpuser'], $_FWGLOBAL['setting']['ftppassword'])) {
				if($_FWGLOBAL['setting']['pasv']) {
					file::sftp_pasv($ftpconnid, TRUE);
				}
				if(file::sftp_chdir($ftpconnid, $_FWGLOBAL['setting']['ftpdir'])) {
					return $ftpconnid;
				} else {
					runlog('FTP', "CHDIR '{$_FWGLOBAL[setting][ftpdir]}' ERROR.", 0);
				}
			} else {
				runlog('FTP', '530 NOT LOGGED IN.', 0);
			}
		} else {
			runlog('FTP', "COULDN'T CONNECT TO {$_FWGLOBAL[setting][ftphost]}:{$_FWGLOBAL[setting][ftpport]}.", 0);
		}
		file::sftp_close($ftpconnid);
		return -1;
	}
	
	public static function sftp_mkdir($ftp_stream, $directory)
	{
		$directory = file::wipespecial($directory);
		return @ftp_mkdir($ftp_stream, $directory);
	}
	
	public static function sftp_rmdir($ftp_stream, $directory)
	{
		$directory = file::wipespecial($directory);
		return @ftp_rmdir($ftp_stream, $directory);
	}
	
	public static function sftp_put($ftp_stream, $remote_file, $local_file, $mode, $startpos = 0 )
	{
		$remote_file = file::wipespecial($remote_file);
		$local_file = file::wipespecial($local_file);
		$mode = intval($mode);
		$startpos = intval($startpos);
		return @ftp_put($ftp_stream, $remote_file, $local_file, $mode, $startpos);
	}
	
	public static function sftp_size($ftp_stream, $remote_file)
	{
		$remote_file = file::wipespecial($remote_file);
		return @ftp_size($ftp_stream, $remote_file);
	}
	
	public static function sftp_close($ftp_stream)
	{
		return @ftp_close($ftp_stream);
	}
	
	public static function sftp_delete($ftp_stream, $path)
	{
		$path = file::wipespecial($path);
		return @ftp_delete($ftp_stream, $path);
	}
	
	public static function sftp_get($ftp_stream, $local_file, $remote_file, $mode, $resumepos = 0)
	{
		$remote_file = file::wipespecial($remote_file);
		$local_file = file::wipespecial($local_file);
		$mode = intval($mode);
		$resumepos = intval($resumepos);
		return @ftp_get($ftp_stream, $local_file, $remote_file, $mode, $resumepos);
	}
	
	public static function sftp_login($ftp_stream, $username, $password)
	{
		$username = file::wipespecial($username);
		$password = str_replace(array("\n", "\r"), array('', ''), $password);
		return @ftp_login($ftp_stream, $username, $password);
	}
	
	public static function sftp_pasv($ftp_stream, $pasv)
	{
		$pasv = intval($pasv);
		return @ftp_pasv($ftp_stream, $pasv);
	}
	
	public static function sftp_chdir($ftp_stream, $directory)
	{
		$directory = file::wipespecial($directory);
		return @ftp_chdir($ftp_stream, $directory);
	}
	
	public static function sftp_site($ftp_stream, $cmd)
	{
		$cmd = file::wipespecial($cmd);
		return @ftp_site($ftp_stream, $cmd);
	}
	
	public static function sftp_chmod($ftp_stream, $mode, $filename)
	{
		$mode = intval($mode);
		$filename = file::wipespecial($filename);
		if(function_exists('ftp_chmod')) {
			return @ftp_chmod($ftp_stream, $mode, $filename);
		} else {
			return file::sftp_site($ftp_stream, 'CHMOD '.$mode.' '.$filename);
		}
	}
	
	public static function wipespecial($str)
	{
		return str_replace(array('..', "\n", "\r"), array('', '', ''), $str);
	}
	
	public static function readFile($filename)
	{
		$content = '';
		if(function_exists('file_get_contents')) {
			@$content = file_get_contents($filename);
		} else {
			if(@$fp = fopen($filename, 'r')) {
				@$content = fread($fp, filesize($filename));
				@fclose($fp);
			}
		}
		return $content;
	}
	
	public static function writeFile($filename, $writetext, $openmod='w')
	{
		if(@$fp = fopen($filename, $openmod)) {
			flock($fp, 2);
			fwrite($fp, $writetext);
			fclose($fp);
			return true;
		} else {
			runlog('error', "File: $filename write error.");
			return false;
		}
	}
	
	public static function readDir($dir, $extarr=array())
	{
		$dirs = array();
		if($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if(!empty($extarr) && is_array($extarr)) {
					if(in_array(strtolower(fileext($file)), $extarr)) {
						$dirs[] = $file;
					}
				} else if($file != '.' && $file != '..') {
					$dirs[] = $file;
				}
			}
			closedir($dh);
		}
		return $dirs;
	}
	
	public static function delTreeDir($dir,$clearDir = false)
	{
		$files = file::readDir($dir);
		foreach ($files as $file) {
			if(is_dir("$dir/$file")) {
				deltreedir("$dir/$file");
				if ($clearDir)
				{
					@rmdir("$dir/$file");
				}
			} else {
				@unlink("$dir/$file");
			}
		}
	}
	
	public static function mkEmptyDir($dir,$mode = 0777)
	{
		mkdir($dir, $mode);
		file_put_contents($dir.DIRECTORY_SEPARATOR.'index.htm','powered by PHPCORE!');
		return true;
	}
}
?>