<?php
/**
 * 每个类的分页器
 */

class filter{
	
	private $select;
	private $orderBySql;
	private $conditionSql;
	private $startPage;
	private $size;
	private $useIndex;
	
	public function  __construct(){
		$this -> select = '';
		$this -> orderBySql = '';
		$this -> conditionSql = '';
		$this -> startPage = 0;
		$this -> size = 0;
		$this -> useIndex = 0;
	}	
	public function __destruct(){
		unset($this -> select);
		unset($this -> orderBySql);
		unset($this -> conditionSql);
		unset($this -> startPage);
		unset($this -> size);
		unset($this -> useIndex);
	}
	/**
	 * @return the $useIndex
	 */
	public function getUseIndex() {
		return $this->useIndex;
	}

	/**
	 * @param number $useIndex
	 */
	public function setUseIndex($useIndex) {
		$this->useIndex = $useIndex;
	}

	/**
	 * @return the $select
	 */
	public function getSelect() {
		return $this->select;
	}

	/**
	 * @return the $orderBySql
	 */
	public function getOrderBySql() {
		return $this->orderBySql;
	}

	/**
	 * @return the $conditionSql
	 */
	public function getConditionSql() {
		return $this->conditionSql;
	}

	/**
	 * @return the $startPage
	 */
	public function getStartPage() {
		return $this->startPage;
	}

	/**
	 * @return the $size
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @param string $select
	 */
	public function setSelect($select) {
		$this->select = $select;
	}

	/**
	 * @param string $orderBySql
	 */
	public function setOrderBySql($orderBySql) {
		$this->orderBySql = $orderBySql;
	}

	/**
	 * @param string $conditionSql
	 */
	public function setConditionSql($conditionSql) {
		$this->conditionSql = $conditionSql;
	}

	/**
	 * @param number $startPage
	 */
	public function setStartPage($startPage) {
		$this->startPage = $startPage;
	}

	/**
	 * @param number $size
	 */
	public function setSize($size) {
		$this->size = $size;
	}
	
	public function getSql(){
		$sql = self::getConditionSql();
		
		$orderBySql = self::getOrderBySql();
		if(!empty($orderBySql)){
			$sql .= ' ORDER BY '.$orderBySql;
		}
		$page = self::getStartPage();
		$page = !empty($page) ? $page : 0;
		$size = self::getSize();
		
		if(!empty($size)){
			$sql .= ' LIMIT '.$page.','.$size;
		}
		return $sql;
	}
}
?>