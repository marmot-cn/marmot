<?php
namespace System\Core;

use PDO;

class MyPdo extends PDO {
	private $error;
	private $sql;
	private $bind;
	private $errorCallbackFunction;
	private $errorMsgFormat;

	/**
	 * @Inject("database.tablepre")
	 */
	private $tablepre;

	/**
	 * @Inject({"database.dsn","database.user","database.passwod"})
	 */
	public function __construct($dsn, $user="", $passwd="") {
		$options = array(
			PDO::ATTR_PERSISTENT => false, 
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		try {
			parent::__construct($dsn, $user, $passwd, $options);
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
		}
	}

	private function debug() {
		if(!empty($this->errorCallbackFunction)) {
			$error = array("Error" => $this->error);
			if(!empty($this->sql))
				$error["SQL Statement"] = $this->sql;
			if(!empty($this->bind))
				$error["Bind Parameters"] = trim(print_r($this->bind, true));

			$backtrace = debug_backtrace();
			if(!empty($backtrace)) {
				foreach($backtrace as $info) {
					if($info["file"] != __FILE__)
						$error["Backtrace"] = $info["file"] . " at line " . $info["line"];	
				}		
			}

			$msg = "";
			if($this->errorMsgFormat == "html") {
				if(!empty($error["Bind Parameters"]))
					$error["Bind Parameters"] = "<pre>" . $error["Bind Parameters"] . "</pre>";
				$css = trim(file_get_contents(dirname(__FILE__) . "/error.css"));
				$msg .= '<style type="text/css">' . "\n" . $css . "\n</style>";
				$msg .= "\n" . '<div class="db-error">' . "\n\t<h3>SQL Error</h3>";
				foreach($error as $key => $val)
					$msg .= "\n\t<label>" . $key . ":</label>" . $val;
				$msg .= "\n\t</div>\n</div>";
			}
			elseif($this->errorMsgFormat == "text") {
				$msg .= "SQL Error\n" . str_repeat("-", 50);
				foreach($error as $key => $val)
					$msg .= "\n\n$key:\n$val";
			}

			$func = $this->errorCallbackFunction;
			$func($msg);
		}
	}

	public function delete($table, $wheresqlArr, $bind="") {

		$table = $this->tname($table);

		$where = $comma = '';
		if(empty($wheresqlArr)) {
			$where = '1';
		} elseif(is_array($wheresqlArr)) {
			foreach ($wheresqlArr as $key => $value) {
				$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
				$comma = ' AND ';
			}
		}else{
			$where = $wheresqlArr;
		}
		
		$sql = 'DELETE FROM ' . $table . ' WHERE ' . $where;
		$this->run($sql, $bind);
	}

	private function filter($table, $info) {

		$sql = "DESCRIBE " . $table . ";";
		$key = "Field";
		// $driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);
		// if($driver == 'sqlite') {
		// 	$sql = "PRAGMA table_info('" . $table . "');";
		// 	$key = "name";
		// }
		// elseif($driver == 'mysql') {
		// 	$sql = "DESCRIBE " . $table . ";";
		// 	$key = "Field";
		// }
		// else {	
		// 	$sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
		// 	$key = "column_name";
		// }	

		if(false !== ($list = $this->run($sql))) {

			$fields = array();
			foreach($list as $record)
				$fields[] = $record[$key];
			return array_values(array_intersect($fields, array_keys($info)));
		}
		return array();
	}

	private function cleanup($bind) {
		if(!is_array($bind)) {
			if(!empty($bind))
				$bind = array($bind);
			else
				$bind = array();
		}
		return $bind;
	}

	public function insert($table, $info) {

		$table = $this->tname($table);

		// $fields = $this->filter($table, $info);

		foreach($info as $key=>$val){
        	$cols[]=$key;
            $vals[]="'".$val."'";
        }

		$sql = 'INSERT INTO ' . $table . ' (' . implode($cols, ', ') . ') VALUES (' . implode($vals, ', ') . ')';
		// $bind = array();
		// foreach($fields as $field)
		// 	$bind[":$field"] = $info[$field];
		return $this->run($sql, $bind);
	}

	public function run($sql, $bind="") {
		$this->sql = trim($sql);
		$this->bind = $this->cleanup($bind);
		$this->error = "";

		try {
			// var_dump($this->sql);exit();
			$pdostmt = $this->prepare($this->sql);
			if($pdostmt->execute($this->bind) !== false) {
				if(preg_match("/^(" . implode("|", array("select", "describe", "pragma")) . ") /i", $this->sql)){
					return $pdostmt->fetchAll(PDO::FETCH_ASSOC);
				}
				elseif(preg_match("/^(" . implode("|", array("delete", "update")) . ") /i", $this->sql)){
					return $pdostmt->rowCount();
				}
				elseif(preg_match("/^(" . implode("|", array("insert")) . ") /i", $this->sql)){
					return $this->lastInsertId();
				}
			}	
		} catch (PDOException $e) {
			$this->error = $e->getMessage();	
			$this->debug();
			return false;
		}
	}

	public function select($table, $where="", $fields="*",$bind="") {

		$table = $this->tname($table);

		$sql = "SELECT " . $fields . " FROM " . $table;
		if(!empty($where))
			$sql .= " WHERE " . $where;
		$sql .= ";";
		return $this->run($sql, $bind);
	}

	public function setErrorCallbackFunction($errorCallbackFunction, $errorMsgFormat="html") {
		//Variable functions for won't work with language constructs such as echo and print, so these are replaced with print_r.
		if(in_array(strtolower($errorCallbackFunction), array("echo", "print")))
			$errorCallbackFunction = "print_r";

		if(function_exists($errorCallbackFunction)) {
			$this->errorCallbackFunction = $errorCallbackFunction;	
			if(!in_array(strtolower($errorMsgFormat), array("html", "text")))
				$errorMsgFormat = "html";
			$this->errorMsgFormat = $errorMsgFormat;	
		}	
	}

	public function update($table, $setsqlArr, $wheresqlArr, $bind="") {

		$table = $this->tname($table);

		// $fields = $this->filter($table, $info);
		// $fieldSize = sizeof($fields);

		// $sql = "UPDATE " . $table . " SET ";
		// for($f = 0; $f < $fieldSize; ++$f) {
		// 	if($f > 0)
		// 		$sql .= ", ";
		// 	$sql .= $fields[$f] . " = :update_" . $fields[$f]; 
		// }
		// $sql .= " WHERE " . $where . ";";

		// $bind = $this->cleanup($bind);
		// foreach($fields as $field)
		// 	$bind[":update_$field"] = $info[$field];

		$setsql = $comma = '';
		if (is_array($setsqlArr)){
			$noComma = false;
			foreach ($setsqlArr as $set_key => $set_value) {
				$setsql .= $comma.'`'.$set_key.'`'.'='.($noComma?$set_value:('\''.$set_value.'\''));
				$comma = ', ';
				$noComma = false;
			}
		}else{
			$setsql .= $setsqlArr;
		}
		$where = $comma = '';
		if(empty($wheresqlArr)) {
			$where = '1';
		} elseif(is_array($wheresqlArr)) {
			foreach ($wheresqlArr as $key => $value) {
				$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
				$comma = ' AND ';
			}
		} else {
			$where = $wheresqlArr;
		}
		$sql = 'UPDATE '.$table.' SET '.$setsql.' WHERE '.$where;

		return $this->run($sql, $bind);
	}

	/**
	 * 为表添加前缀
	 */
	private function tname($table){

		return $this->tablepre.$table;
	}
}	
?>
