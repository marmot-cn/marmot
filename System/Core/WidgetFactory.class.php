<?php 
if(!defined('IN_PHP')) {
	exit('Access Denied');
}

class WidgetFactory {
	
	/**
	 *
	 * @param string $name 钩子类型<文件夹>
	 * @param string $action 钩子动作文件
	 * @param string $method 调用方法
	 * @param string $modName 跨模块调用传模块名字 MOD_moduleName
	 * @param mixed $data 参数参数
	 * @throws Exception
	 * @return mixed
	 */
	public static function widget($actionName,$data='',$modName=''){
		try{
			$filePath = empty($modName) ? M_ROOT.'widget/'.$actionName.'.widget.php' : S_ROOT.$modName.'/widget/'.$actionName.'.widget.php';
			if(!file_exists($filePath)){
				throw new Exception('No such widget file');
			}else{
				include_once $filePath;
				if(class_exists($actionName)){
					$widget = new $actionName;
					return $widget -> render($data);
				}else{
					throw new Exception('No such widget class');
				}
			}
	
		}catch (Exception $e){
			return $e;
		}
	}
}
?>