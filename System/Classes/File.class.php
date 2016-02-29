<?php
namespace System\Classes;

class File{
	
	private $fileId = 0; 		//上传文件主键
	private $fileName	= '';		//文件名
	private $fileTemp	= '';		//临时文件地址
	private $fileExt	= '';		//文件扩展名
	private $filePath	= '';		//文件存储路径
	private $fileErr	= '';		//上传错误代码
	private $fileHash	= '';		//文件Hash值
	private $fileSize	= '';		//文件大小（单位G/M/K）
	private $fileTime	= '';		//上传文件时间戳
	private $fileOwner	= 'user';	//文件所属（user:个人;unit:组织;System:系统后台),预留
	private $fileUid	= '';		//文件所属id（和fileOwner字段挂钩,预留
	
	private $fileData		= array();	//下载文件序列
	private $thumbAuto		= false;	//自动增加缩略图,暂未实现
	private $watermarkAuto	= false;	//自动增加水印,赞为实现

	/**
	 * @Inject("file.uploadDirType")
	 */
	private $uploadDirType;

	/**
	 * @Inject("file.attachDir")
	 */
	private $attachDir;

	/**
	 * @Inject("file.siteUrl")
	 */
	private $siteUrl;

	/**
	 * @Inject("System\Persistence\FileCache")
	 */
	private $cacheLayer;//缓存层	

	/**
	 * @Inject("System\Persistence\FileDb")
	 */
	private $dbLayer;//数据层

	private $fileTypeData	= array(	//所有支持的文档
			'av' => array('av', 'avi', 'wmv', 'wav'),
			'real' => array('rm', 'rmvb'),
			'binary' => array('dat'),
			'flash' => array('swf'),
			'html' => array('html', 'htm'),
			'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
			'office' => array('doc', 'xls', 'ppt'),
			'pdf' => array('pdf'),
			'rar' => array('rar', 'zip'),
			'text' => array('txt'),
			'bt' => array('bt'),
			'zip' => array('tar', 'rar', 'zip', 'gz'),
		);
	private $fileTypeAllow	= array(	//允许上传的类型组key（无需转义后缀）
			'av','real','flash','image','office','pdf','rar','text'
		);
	
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
	
	//记录数据库
	private function saveFileToDB(){
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
		$fileId = $this->dbLayer->insert(saddslashes($setArr),true);

		$setArr['fileId'] = $fileId;
		return $setArr;
	}
	
	//缓存写文件　
	private function cacheWriteFile($setArr){
		return $this->cacheLayer->save($setArr['fileId'],$setArr);
	}
	//缓存读文件
	private function cacheReadFile($fileId){
		return $this->cacheLayer->get($fileId);
	}
	//基础错误判断
	private function check_fileTemp(){
		if(empty($this->fileSize)){
			return false;
			showmessage('无法获取文件大小');
		}
		if (empty($this->fileTemp)){
			return false;
			showmessage('上传到临时文件夹错误');
		}
		if (!empty($this->fileErr)){
			return false;
			showmessage('uploadError:'.$this->fileErr);
		}
		return true;
	}
	
	//获取路径
	private function getPath($fileName = ''){

		if ($this->uploadDirType == 'date'){
			$name1 = gmdate('Ym');
			$name2 = gmdate('j');
		}elseif ($this->uploadDirType == 'ext'){
			$name1 = $fileExt = isset($fileName)?$this->getFileExt($fileName):$this->fileExt;
			$name2 = gmdate('Ymj');
		}else{
			//默认时间
			$name1 = gmdate('Ym');
			$name2 = gmdate('j');
		}
		//判断第一层文件夹
		$newfilename = $this->attachDir.$name1.DIRECTORY_SEPARATOR;
		if(!is_dir($newfilename)) {
			if(!@mkdir($newfilename,0777)) {
				// runlog('error', "DIR: $newfilename can not make");
				return false;
			}
		}
		//判断第二层文件夹
		$newfilename .= $name2.DIRECTORY_SEPARATOR;
		if(!is_dir($newfilename)) {
			if(!@mkdir($newfilename,0777)) {
				// runlog('error', "DIR: $newfilename can not make");
				return false;
			}
		}
		//路径赋值
		$this->filePath = $name1.'/'.$name2.'/'.$this->fileHash.'.'.$this->fileExt;
		//返回服务器路径
		return $newfilename.$this->fileHash.'.'.$this->fileExt;
	}
	
	//获取后缀
	private function getFileExt($fileName = ''){
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
	public static function getFileURL($filePath){
		return str_replace(array(
							$this->attachDir,
							S_ROOT,
							'\\'
						),array(
							$this->attachDir,
							$this->siteUrl,
							'/'
						),$filePath);
	}
	
	//获取文件大小
	private function getFileSize($fileSize = ''){
		$fileSize = empty($fileSize)?$this->fileSize:$fileSize;
		return file::formatsize($fileSize);
	}
	
	//设定文件所属单位
	public function setFileOwner($uid = 0, $owner = ''){
		// $this->fileOwner	= ($owner == 'unit')?'unit':'user';
		// if ($this->fileOwner == 'user')
		// {
		// 	global $_FWGLOBAL;
		// 	$this->fileUid = empty($uid)?$_FWGLOBAL['pcore_uid']:intval($uid);
		// 	if (empty($this->fileUid))
		// 	{
		// 		return false;
		// 	}
		// }elseif (empty($uid)){
		// 	return false;
		// }else {
		// 	$this->fileUid = intval($uid);
		// }
		// return true;
	}
	
	//格式化文件大小
	public static function formatsize($size){
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
	public static function fileMove($formFilePath, $toFilePath, $deleteFile = false){
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
	// public function download($hash){
	// 	$this->getFileData($hash);
	// 	$this->add_downloadCount($hash);
	// 	return $this->fileData[$hash];
	// }
	
	public function getFileData($fileId){
		
		if(empty($fileId)){
			return false;
		}
		$result = $this -> cacheReadFile($fileId);
		if (empty($result)){
			$result = $this->dbLayer->select('fileId='.$fileId);
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
			$result = $list = array();

			$result = $this->dbLayer->select('fileId IN ('.$fids.')');
			if(!empty($result)){
				foreach($result as $value){
					$list[$value['fileId']] = $value;
					$this -> cacheWriteFile($value);
				}
			}
		}
		return $list;
	}
}
?>