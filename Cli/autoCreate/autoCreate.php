<?php
include 'TemplateInterface.class.php';

$operation = isset($argv[2]) ? explode('=',$argv[2]) : '';

if(!empty($operation) && is_array($operation) && $operation[0] == '--addModel'){

	createModelFile($operation);
	createModelUnitTestFile($operation);

}elseif(!empty($operation) && is_array($operation) && $operation[0] == ''){


}else{

	echo "Usage: php marmot.php autoCreate --options='profile'\n";
	echo "Options:\n";
	echo "\t--addModel='profile'\tAdd new model file and unitTest file by using profile\n";
}

function createModelFile($operation){

	include_once 'ModelTemplate.class.php';
	$modTemplate = new ModelTemplate();
	if(!$modTemplate->loadProfile($operation[1])){
		runFail($operation[1].' load profile fail');
	}
	if($modTemplate->generate()){
		runSucess($operation[1].' model file create complete...');
	}else{
		runFail($operation[1].' model file create fail');
	}
}

function createModelUnitTestFile($operation){

	include_once 'ModelUnitTestTemplate.class.php';
	$modelUnitTestTemplate = new ModelUnitTestTemplate();
	if(!$modelUnitTestTemplate->loadProfile($operation[1])){
		runFail($operation[1].' load profile fail');
	}
	if($modelUnitTestTemplate->generate()){
		runSucess($operation[1].' unitTest file create complete...');
	}else{
		runFail($operation[1].' unitTest file create fail');
	}
}