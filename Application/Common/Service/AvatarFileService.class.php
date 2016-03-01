<?php
namespace Common\Service;
use Intervention\Image\ImageManagerStatic as ImageThumb;
use Core;
/**
 * 头像图片文件角色
 * 
 * @author chloroplast
 * @version 1.0:20160227
 */

class AvatarFileService {

	/**
	 * @var Common\Model\File $file 文件对象
	 */
	private $file;
	/**
	 * @var integer $width 宽度
	 */
	private $width = '100';
	/**
	 * @var integer $height 高度
	 */
	private $height = '100';

	public function __construct(){
		$this->file = Core::$_container->get('Common\Model\File');
	}

	/**
	 * 上传头像
	 * 
	 * @param $_FILE $FILE
	 * @return [] array('fileId'=>文件id,'filePath'=>文件路径)
	 */
	public function upload($FILE){

		//初始化构造函数
		$result = array('fileId'=>0,'filePath'=>'');

		$fileInfo = $this->file->upload($FILE);

		$imgPath = $this->file->getAttachDir().$fileInfo['filePath'];

		//根据fileInfo进行缩略图设置
		$thumbImg = ImageThumb::make($imgPath);
		$thumbImg->resize($this->width,$this->height);

		$filePathInfo = pathinfo($imgPath);

		$thumbImg->save($filePathInfo['dirname'].DIRECTORY_SEPARATOR.$filePathInfo['filename'].'.'.$this->width.'_'.$this->height.'.'.$filePathInfo['extension']);

		//拼接返回数组
		$result['fileId'] = $fileInfo['fileId'];
		$result['filePath'] = $imgPath;

		return $result;
	}

	/**
	 * 根据文件id获取头像地址,和缩略图地址
	 * @param intger $id 文件id
	 * @return string 文件路径
	 */
	public function getOne(int $id){

		if(empty($id)){
			return false;
		}
		$fileInfo =  $this->file->getFileData($id);

		return $this->file->getAttachDir().$fileInfo['filePath'];
	}
}
?>