<?php

namespace Controller;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class HomeController {

	/**
	 * 测试调取用户信息页面
	 */
	public function index(){

// 		$a = new \Persistence\UserCache();
		
// 		$number = '11';
// 		$result = v::numeric()->validate($number); // true
// 		// $result = v::numeric()->assert('really messed up screen#name');

// 		$usernameValidator = v::numeric();
// 		$usernameValidator->validate('aa'); // true
// 		try {
//     $usernameValidator->assert('ttt');

// } catch(NestedValidationException $exception) {
// 		print_r($exception->getMessages());
//  //   $errors = $exception->findMessages([
//  //    'numeric' => array('11233')
// 	// ]);
//  //   print_r($errors);
// }

		// exit();
		echo 'Hello World';
		exit();
		// $productService = new \Service\Product\NormalProductService($a,$b,$c);

		// $productAdminService = new \Service\User\productAdminService($d,$e);
				
		// $productAdminService -> createNormalProduct($productService);
	}

	// public function car($a,$b,$c,$d){


	// 	$productService = new \Service\Product\CarService($a,$b,$c);

	// }

}