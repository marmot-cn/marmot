<?php
namespace Controller;
use Core;
	error_reporting(6143);
class UserController {

	/**
	 * 测试调取用户信息页面
	 */
	public function index(){

		$user = Core::$_container -> get('\Model\User\User');
		var_dump($user);
		// $user->product = new \Model\Product\Product();
		// $user->product->pname = 'tt';
		// $user->uid = 12;
	

		// var_dump($user->product->pname);
		// var_dump($user->uid);
		// var_dump($user->getP());
		//$unRegisterUserService = \Core::$_container -> get('Service\User\UnregisterUserService');
		// $registerUserService = new \Service\User\RegisterUserService();会报错,用容器解决依赖,自动绑定
		//var_dump($unRegisterUserService);
		//exit();

		// $user = \Core::$_container->get('\Model\User\User');
		// $user->doSomething();
		// $user->product->pname = 'aa';
		// var_dump($user->product->pname);

		// $user = \Core::$_container->get('\Model\User\User');
		// var_dump($user->product->pname);

		// $id = \Persistence\UserDb::insert(array('userName'=>'name','age'=>22,'nickName'=>'nickName'));
		// \Persistence\UserDb::insert(array('userName'=>'name','age'=>22,'nickName'=>'nickName'));
		// var_dump($id);
		// exit();
		// $id = \Persistence\UserDb::update(array('age'=>'11'),array('id'=>3));
		// \Persistence\UserDb::insert(array('userName'=>'name1','age'=>33,'nickName'=>'nickName1'));

		// $del = Core::$_dbDriver->prepare("DELETE FROM pcore_user WHERE `userName`='name'");

		// $count = $del->execute();

		/* Return number of rows that were deleted */
		// print("Return number of rows that were deleted:\n");
		// // $count = $del->rowCount();
		// print("Deleted $count rows.\n");

		exit();
	}

	public function get($ids){

				var_dump('show-'.$ids);
		var_dump('show');
		exit();
	}

	public function delete(){
		var_dump('delete');
		exit();

	}
}