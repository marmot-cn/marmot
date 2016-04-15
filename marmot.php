<?php
/**
 * 自动工具包
 */

define('CLI_VERSION','1.0.20160413');

include_once 'Core.php';
$core = Core::getInstance();
$core -> initCli();

$aliasCommands = ['-h'=>'help',
				  '-v'=>'version'];

$command = isset($argv[1]) ? $argv[1] : ''; //获取命令

$command = isset($aliasCommands[$command]) ? $aliasCommands[$command] : $command;
//如果没有给出操作符,则调用默认操作 同命令文件

// class cliWindow{

// 	private $cols;
// 	private $rows;

// 	public function __construct(){
// 		$this->cols = 0;
// 		$this->rows = 0;
// 		//初始化cli窗口
// 		newt_init();
// 		newt_get_screen_size ($this->cols, $this->rows);
// 		newt_finished();
// 	}

// 	public function runSucess($result){
// 		$alignment = $this->rows-8;

// 		printf("%".$alignment."s", "[  \033[42mok\033[0m  ]\n");
// 	}

// 	public function runFail($result){
// 		$alignment = $this->rows-8;

// 		printf("%".$alignment."s", "[ \033[41mfail\033[0m ]\n");
// 	}
// }

// $cliWindow = new cliWindow();

function runSucess($result){
	$strlen = strlen($result);
	$alignment = 100 - $strlen;
	printf("%s %".$alignment."s",$result, "[  \033[0;32mok\033[0m  ]\n");
}

function runFail($result){
	$strlen = strlen($result);
	$alignment = 100 - $strlen;
	printf("%s  %".$alignment."s",$result, "[ \033[0;31mfail\033[0m ]\n");
}
//调用命令文件
if(!file_exists('./Cli/'.$command.'/'.$command.'.php')){
	include './Cli/help/help.php';//调用默认命令
	exit(0);
}
include './Cli/'.$command.'/'.$command.'.php';
