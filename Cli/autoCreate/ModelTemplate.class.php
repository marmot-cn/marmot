<?php

class ModelTemplate implements TemplateInterface{

	private $profileData;
	private $buffer;

	/**
	 * 加载配置文件信息
	 */
	public function loadProfile($profile){
		if(is_file('Cli/autoCreate/'.$profile)){
			$this->profileData = include 'Cli/autoCreate/'.$profile;
			return true;
		}
		return false;
	}

	/**
	 * 生成model模板文件
	 */
	public function generate(){

		$this->generateBeginInfo();
		$this->generateParameters();
		$this->generateConstructFunction();
		$this->generateDestructFunction();
		$this->generateSetGetFunctions();
		$this->generateEndInfo();

		return file_put_contents('Cli/autoCreate/Model/'.$this->profileData['className'].'.class.php',$this->buffer);
	}

	/**
	 * 生成文件开始信息
	 */
	public function generateBeginInfo(){
		$this->buffer .= "<?php";
		$this->buffer .= "\nnamespace ".$this->profileData['nameSpace'].";";
		$this->buffer .= "\n";
		$this->buffer .= "\n/**";
		$this->buffer .= "\n * ".$this->profileData['className']." ".$this->profileData['comment'];
		$this->buffer .= "\n * @author chloroplast";
		$this->buffer .= "\n * @version 1.0.0:".date('Y.m.d',time());
		$this->buffer .= "\n */";
		$this->buffer .= "\n\nclass ".$this->profileData['className']."{\n";
	}

	/**
	 * 生成文件结束信息
	 */
	public function generateEndInfo(){

		$this->buffer .= "}";
	}

	/**
	 * 生成变量
	 */
	private function generateParameters(){

		$this->buffer .= "\n";

		foreach($this->profileData['parameters'] as $parameter){
			$this->buffer .= "\t/**\n\t * @var ".$parameter['type']." $".$parameter['key']." ".$parameter['comment']."\n\t */\n\tprivate $".$parameter['key']."\n";
		}
	}

	/**
	 * 生成构造函数
	 */
	private function generateConstructFunction(){
		$this->buffer .= "\n";

		$this->buffer .= "\t/**\n\t * ".$this->profileData['className']." ".$this->profileData['comment']." 构造函数"."\n\t */\n";

		$this->buffer .= "\tpublic function __construct(){\n";
		$this->buffer .= "\t\tglobal ".'$_FWGLOBAL'.";\n";
		foreach($this->profileData['parameters'] as $parameter){
			$default = $parameter['default'] !== '' ? $parameter['default'] : "''";

			$this->buffer .= "\t\t".'$this->'.$parameter['key']." = ".$default.";\n";
		}
		$this->buffer .= "\t}\n";
	}

	/**
	 * 生成析构函数
	 */
	private function generateDestructFunction(){
		$this->buffer .= "\n";

		$this->buffer .= "\t/**\n\t * ".$this->profileData['className']." ".$this->profileData['comment']." 析构函数"."\n\t */\n";

		$this->buffer .= "\tpublic function __destruct(){\n";
		foreach($this->profileData['parameters'] as $parameter){
			$this->buffer .= "\t\tunset(".'$this->'.$parameter['key'].");\n";
		}
		$this->buffer .= "\t}\n";
	}

	/**
	 * 生成set,get函数对
	 */
	private function generateSetGetFunctions(){
		$this->buffer .= "\n";

		foreach($this->profileData['parameters'] as $parameter){
			//生成set
			//添加注释
			$this->buffer .= "\t/**\n\t * 设置".$parameter['comment']."\n\t * @param ".$parameter['type']." $".$parameter['key']." ".$parameter['comment']."\n\t */\n";

			$this->buffer .= "\tpublic function set".ucfirst($parameter['key'])."(".$parameter['type'].' $'.$parameter['key']."){\n";
			if(empty($parameter['rule'])){
				$this->buffer .= "\t\t".'$this->'.$parameter['key']." = ".'$'.$parameter['key'].";\n";
			}else if($parameter['rule']=='cellPhone'){
				$this->buffer .= "\t\t".'$this->'.$parameter['key']." = is_numeric(".'$'.$parameter['key'].") ? ". '$'.$parameter['key']." : ''\n";
			}else if($parameter['rule']=='qq'){
				$this->buffer .= "\t\t".'$this->'.$parameter['key']." = is_numeric(".'$'.$parameter['key'].") ? ". '$'.$parameter['key']." : ''\n";
			}else if($parameter['rule']=='email'){
				$this->buffer .= "\t\t".'$this->'.$parameter['key']."= filter_var(".'$'.$parameter['key'].", FILTER_VALIDATE_EMAIL) ? ".'$'.$parameter['key']." : ''\n";
			}else if($parameter['rule']=='object'){
				$this->buffer .= "\t\t".'$this->'.$parameter['key']." = ".'$'.$parameter['key'].";\n";
			}
			else if($parameter['rule']=='time'){
				$this->buffer .= "\t\t".'$this->'.$parameter['key']." = ".'$'.$parameter['key'].";\n";
			}else if(is_array($parameter['rule'])){

				$this->buffer .= "\t\t".'$this->'.$parameter['key']."= in_array(".'$'.$parameter['key'].",array(";
				$comma = '';
				foreach($parameter['rule'] as $rule){
					$this->buffer .= $comma.$rule;
					$comma = ',';
				}
				$this->buffer .= ")) ? ".'$'.$parameter['key']." : ".$parameter['default'].";\n";
			}
			$this->buffer .= "\t}\n";
			$this->buffer .= "\n";

			//生成get
			//添加注释
			$this->buffer .= "\t/**\n\t * 获取".$parameter['comment']."\n\t * @return ".$parameter['type']." $".$parameter['key']." ".$parameter['comment']."\n\t */\n";
			$this->buffer .= "\tpublic function get".ucfirst($parameter['key'])."(){\n";
			$this->buffer .= "\t\treturn ".'$this->'.$parameter['key'].";\n";
			$this->buffer .= "\t}\n";
			$this->buffer .= "\n";
		}

	}
}