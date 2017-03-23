<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 28/01/2017
 * Time: 18:35
 */
require("class_core.php");

class category extends Core {
	protected $category_id;
	private $category_name;
	private $category_description;
	private $published;
	private $create_time;
	private $created_by;

	public function __construct() {
		global $db;
		$this->setValidateMessages(array());
		$this->setFailedValidation(0);
		$this->setMainTable('categories');
		$this->setPk('category_id');
		$this->setDbObj($db);
	}

	public function getCategoryId() {
		return $this->category_id;
	}

	public function setCategoryId($category_id) {
		$this->category_id = $category_id;
	}

	public function getCategory() {
		$this->sql = "SELECT * FROM $this->main_table WHERE $this->pk= $this->category_id";
		if (!$records = $this->db_obj->GetRow($this->sql)) {
			return false;
		}

		return $records;
	}

	public function getAllCategories() {
		$this->sql = "SELECT * FROM $this->main_table WHERE published=1";
		if (!$records = $this->db_obj->GetAll($this->sql)) {
			return false;
		}

		return $records;
	}

	public function insertCategory() {
		if (!$this->createData()) return false;

		return true;
	}

	public function updateCategory() {
		$this->setId($this->category_id);
		if (!$this->updateData()) return false;

		return true;
	}

	public function deleteCategory() {
		$this->setId($this->category_id);
		if (!$this->deleteData()) return false;

		return true;
	}

	public function validateCategory($mode) {
		if ($mode == 'edit') {
			if (empty($this->category_id) || !is_numeric($this->category_id)) {
				$this->validate_messages['category_id'] = 'Category ID cannot be empty';
				$this->failed_validation++;
			}
		}
		if ($this->category_name == '' || is_null($this->category_name)) {
			$this->validate_messages['category_name'] = 'Please enter a valid name';
			$this->failed_validation++;
		}
		if ($this->created_by == '' || is_null($this->created_by)) {
			$this->validate_messages['created_by'] = 'Your session has expired. Please login again';
			$this->failed_validation++;
		}

		if ($this->failed_validation < 1) {
			$this->data_array = array(
				'category_name'        => $this->getCategoryName(),
				'category_description' => $this->getCategoryDescription(),
				'create_time'          => $this->getCreateTime(),
				'created_by'           => $this->getCreatedBy(),
				'published'            => $this->getPublished()
			);

			return true;
		} else return false;
	}

	public function getCategoryName() {
		return $this->category_name;
	}

	public function setCategoryName($category_name) {
		$this->category_name = $category_name;
	}

	public function getCategoryDescription() {
		return $this->category_description;
	}

	public function setCategoryDescription($category_description) {
		$this->category_description = $category_description;
	}

	public function getCreateTime() {
		return $this->create_time;
	}

	public function setCreateTime($create_time) {
		$this->create_time = $create_time;
	}

	public function getCreatedBy() {
		return $this->created_by;
	}

	public function setCreatedBy($created_by) {
		$this->created_by = $created_by;
	}

	public function getPublished() {
		return $this->published;
	}

	public function setPublished($published) {
		$this->published = $published;
	}
}