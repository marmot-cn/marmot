<?php

namespace System\Core;

abstract class Object {

	private $_properties = array();

	public function __get($name){

		//兼容annotaion,依赖注入
		// if(isset($this->$name)){
		// 	$this->_properties[$name] = $this->$name;
		// 	return $this->$name;
		// }

		if(isset($this->_properties[$name])){
			return $this->_properties[$name];
		}

		return false;
	}

	public function __set($name, $value){

		 // var_dump(11);
		 // exit();
		if(property_exists($this,$name)){
			$this->_properties[$name] = $value;
			return true;
		}

		return false;
	}

	// public function getP(){
	// 	return $this->_properties;
	// }

}

?>