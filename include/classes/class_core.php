<?php

/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 10/03/2017
 * Time: 17:22
 */
class Core {
	protected $data_array;
	protected $sql;
	protected $main_table;
	protected $linked_table;
	protected $validate_messages;
	protected $failed_validation;
	protected $db_obj;
	protected $pk;
	protected $fk;
	protected $id;
	protected $search_string;

	private function __construct() {

	}

	public function getDataArray() {
		return $this->data_array;
	}

	public function setDataArray($data_array) {
		$this->data_array = $data_array;
	}

	public function getSql() {
		return $this->sql;
	}

	public function setSql($sql) {
		$this->sql = $sql;
	}

	public function getMainTable() {
		return $this->main_table;
	}

	public function setMainTable($main_table) {
		$this->main_table = $main_table;
	}

	public function getLinkedTable() {
		return $this->linked_table;
	}

	public function setLinkedTable($linked_table) {
		$this->linked_table = $linked_table;
	}

	public function getValidateMessages() {
		return $this->validate_messages;
	}

	public function setValidateMessages($validate_messages) {
		$this->validate_messages = $validate_messages;
	}

	public function getFailedValidation() {
		return $this->failed_validation;
	}

	public function setFailedValidation($failed_validation) {
		$this->failed_validation = $failed_validation;
	}
	
	public function getDbObj() {
		return $this->db_obj;
	}
	
	public function setDbObj($db_obj) {
		$this->db_obj = $db_obj;
	}

	public function getPk() {
		return $this->pk;
	}

	public function setPk($pk) {
		$this->pk = $pk;
	}

	public function getFk() {
		return $this->fk;
	}

	public function setFk($fk) {
		$this->fk = $fk;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getSearchString() {
		return $this->search_string;
	}

	public function setSearchString($search_string) {
		$this->search_string = str_replace('-', '', $search_string);
	}

	protected function createData() {
		$this->sql = "INSERT INTO $this->main_table(`" . implode("`, `", array_keys($this->data_array)) . "`) VALUES (
		'" . implode("', '", $this->data_array) . "')";
		if (!$this->db_obj->Execute($this->sql)) return false;

		return true;
	}

	protected function updateData() {
		$this->sql = "UPDATE $this->main_table SET ";
		foreach ($this->data_array as $col => $value) {
			$this->sql .= "`$col` = '$value', ";
		}
		$this->sql = trim($this->sql, ', ');
		$this->sql .= " WHERE $this->pk = $this->id";
		if (!$this->db_obj->Execute($this->sql)) return false;

		return true;
	}

	protected function deleteData() {
		$this->sql = "DELETE FROM $this->main_table WHERE $this->pk= $this->id";
		if (!$this->db_obj->Execute($this->sql)) return false;

		return true;
	}

}