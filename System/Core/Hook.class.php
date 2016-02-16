<?php
//powered by phpcore.net
if(!defined('IN_PHP')) {
	exit('Access Denied');
}
/**
 * 钩子类,调用全局钩子文件
 * 调用方法 hook::trigger(validateVenderIdentify,afterVerifyValidateVenderIdentify, testHook, array('data'=>'what u want'));
 * @author chloroplast1983
 * @version 1.0
 */

class Hook {
	
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
	public static function trigger($name,$action,$method,$data,$modName=''){
		try{
			$filePath = empty($modName) ? M_ROOT.'hook/'.$name.'/'.$action.'.hook.php' : S_ROOT.$modName.'/hook/'.$name.'/'.$action.'.hook.php';
			if(!file_exists($filePath)){
				throw new Exception('No such hook file');
			}else{
				include_once $filePath;
				if(class_exists($action)){
					$hook = new $action;
					return $hook -> $method($data);
				}else{
					throw new Exception('No such hook class');
				}
			}
			
		}catch (Exception $e){
			return $e;
		}
	}
}
?>