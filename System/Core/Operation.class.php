<?php
	/**
	 * 进程父类, 用于执行各个对象之间的关系操作
	 * @param object    $fromPort  进程起始端
	 * @param object    $toPort    进程结束段
	 * @param string    $note      进程简介
	 * @param integer   $id        进程id
	 * @param timestamp $time      进程创建时间
	 * 
	 * @version 1.1.20160215
	 * @author chloroplast
	 */
	class Operation {
		
		private $fromPort;
		private $toPort;
		private $note;
		private $id;
		private $time;
		private $package;
		private $status;

		/**
		 * 构造函数,初始化进程。 初始化内部所有变量.
		 *
		 */
		public function __construct(){
			$this->fromPort = null;
			$this->toPort = null;
			$this->package = null;
			$this->note = '';
			$this->id = 0;
			$this->time = 0;
			$this->status = 1;
		}
		/**
		 * 释放所有进程对象。
		 *
		 */
		public function __destruct(){
			$this->fromPort = null;
			$this->toPort = null;
			$this->package = null;
			$this->note = '';
			$this->id = 0;
			$this->time = 0;
			$this->status = 0;
		}
		/**
		 * 初始化进程对象
		 */
		public function __init($fromPort,$package,$toPort=null){
			global $_FWGLOBAL;
			$this->time = $_FWGLOBAL['timestamp'];
			$this->fromPort = $fromPort;
			$this->toPort = $toPort;
			$this->package = $package;
			$this->note = '';
			$this->id = 0;			
		}
		/**
		 * 设定进程起始对象
		 *
		 * @param object $fromPort
		 */
		public function setFromPort($fromPort){
			$this->fromPort = $fromPort;
		}
		/**
		 * 返回进程起始对象
		 *
		 * @return object 起始对象
		 */
		public function getFromPort(){
			return $this->fromPort;
		}
		/**
		 * 设定进程终端对象
		 *
		 * @param object $toPort
		 */
		public function setToPort($toPort){
			$this->toPort = $toPort;
		}
		/**
		 * 返回进程终端对象
		 *
		 * @return object 终端对象
		 */
		public function getToPort(){
			return $this->toPort;
		}
		
		public function setPackage($package){
			$this->package = $package;
		}
		
		public function getPackage(){
			return $this->package;
		}
		/**
		 * 设定进程简介
		 *
		 * @param string $note
		 */
		public function setNote($note){
			$this->note = is_string($note) ? $note : '';
		}
		/**
		 * 返回进程简介
		 *
		 * @return string 进程简介
		 */
		public function getNote(){
			return $this->note;
		}
		/**
		 * 设定进程id
		 *
		 * @param integer $id
		 */
		public function setID($id){
			$this->id = is_integer($id) ? $id : 0;
		}
		/**
		 * 返回进程id
		 *
		 * @return integer id
		 */
		public function getID(){
			return $this->id;
		}
		/**
		 * 刷新时间
		 *
		 */
		public function refreshTime(){
			global $_FWGLOBAL;
			$this->time = $_FWGLOBAL['timestamp'];
		}
		/**
		 * @return the $status
		 */
		public function getStatus() {
			return $this->status;
		}
		
		/**
		 * @param field_type $status
		 */
		public function setStatus($status) {
			$this->status = $status;
		}
		/**
		 * 返回时间
		 *
		 * @return timestamp 进程时间
		 */
		public function getTime(){
			return $this->time;
		}
		public function toString(){	
			return '';
		}
		
		public function report(){
				
		}
	}
?>