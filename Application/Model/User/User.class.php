<?php
namespace Model\User;

use System\Core;


class User extends Core\Object{

	 /**
	 * @Inject("\Model\Product\Product")
	 */
	private $product;

	private $uid;

	private $status = 0;
	private $name;

	public function __construct(){
		
	}

}
?>