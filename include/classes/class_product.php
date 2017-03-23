<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 28/01/2017
 * Time: 18:34
 */

require_once('class_core.php');

class Product extends Core {
	private $product_id;
	private $product_name;
	private $product_short_description;
	private $product_description;
	private $product_price;
	private $product_quantity;
	private $product_image;
	private $create_time;
	private $published;
	private $featured;
	private $category_id;

	public function __construct() {
		global $db;
		$this->setValidateMessages(array());
		$this->setFailedValidation(0);
		$this->setMainTable('products');
		$this->setLinkedTable('categories');
		$this->setPk('product_id');
		$this->setDbObj($db);
	}

	public function getProductId() {
		return $this->product_id;
	}

	public function setProductId($product_id) {
		$this->product_id = $product_id;
	}

	public function getProduct() {
		$this->sql = "SELECT * FROM $this->main_table WHERE product_id= $this->product_id";
		if (!$records = $this->db_obj->GetRow($this->sql)) {
			return false;
		}

		return $records;
	}

	public function getAllProducts($type = '') {
		$this->sql = "SELECT pro.*, cat.category_name FROM $this->main_table pro INNER JOIN $this->linked_table cat ON cat.category_id= pro.category_id WHERE cat.published=1 AND pro.published=1";
		if ($type == 'featured') $this->sql .= " AND pro.featured= 1";
		if ($type == 'category') $this->sql .= " AND pro.category_id= '" . $this->category_id . "'";
		if ($type == 'search') $this->sql .= " AND (pro.product_name LIKE '%" . $this->search_string . "%' OR pro.product_short_description LIKE '%" . $this->search_string . "%' OR pro.product_description LIKE '%" . $this->search_string . "%' OR cat.category_name LIKE '%" . $this->search_string . "%')";
		if (!$records = $this->db_obj->GetAll($this->sql)) {
			return false;
		}

		return $records;
	}

	public function insertProduct() {
		if (!$this->createData())
			return false;
		else {
			$last_insert_id = $this->db_obj->InsertId();
			$file = $this->getProductImage();
			//rename uploaded file
			$file['new_name'] = createNewFileName($file['name'], $last_insert_id);
			//update the product image field of the newly inserted record
			$this->sql = "UPDATE $this->main_table SET product_image= '" . $file['new_name'] . "' WHERE $this->pk= $last_insert_id";
			if (!$this->db_obj->Execute($this->sql)) return false;
			//save image
			if (!saveImage($this->main_table, $file, $last_insert_id)) return false;

			return true;
		}
	}

	public function getProductImage() {
		return $this->product_image;
	}

	public function setProductImage($product_image) {
		$this->product_image = $product_image;
	}

	public function updateProduct() {
		//save image if existing was changed
		if (isset($insert_array['product_image'])) {
			$file = $this->getProductImage();
			//rename uploaded file
			$file['new_name'] = createNewFileName($file['name'], $this->product_id);
			//save image
			if (!saveImage($this->main_table, $file, $this->product_id)) return false;
			$this->data_array['product_image'] = $file['new_name'];
		}
		$this->setId($this->product_id);
		if (!$this->updateData()) return false;

		return true;
	}

	public function deleteProduct() {
		if (deleteImage($this->main_table, $this->product_id)) {
			$this->setId($this->product_id);
			if (!$this->deleteData()) return false;

			return true;
		} else
			return false;
	}

	public function validateProduct($mode) {
		if ($mode == 'edit') {
			if (empty($this->product_id) || !is_numeric($this->product_id)) {
				$this->validate_messages['product_id'] = 'Product ID cannot be empty';
				$this->failed_validation++;
			}
		}
		if ($this->product_name == '' || is_null($this->product_name)) {
			$this->validate_messages['product_name'] = 'Please enter a valid product name';
			$this->failed_validation++;
		}
		if ($this->product_description == '' || is_null($this->product_description)) {
			$this->validate_messages['product_description'] = 'Please describe this product';
			$this->failed_validation++;
		}
		if ($this->product_price == '' || is_null($this->product_price) || !is_numeric($this->product_price)) {
			$this->validate_messages['product_price'] = 'Enter a valid amount';
			$this->failed_validation++;
		}
		if ($this->category_id == '' || is_null($this->category_id) || !is_numeric($this->category_id)) {
			$this->validate_messages['category_id'] = 'Select category for product';
			$this->failed_validation++;
		}
		if ($mode == 'new') {
			if ($this->product_image['name'] == '' || is_null($this->product_image['name'])) {
				$this->validate_messages['product_image'] = 'Please upload a picture';
				$this->failed_validation++;
			} elseif ($this->product_image['size'] > 2000000) { //file is larger than 5mb
				$this->validate_messages['product_image'] = 'Image is too large. Please upload images less than 2mb';
				$this->failed_validation++;
			}

		}

		if ($this->failed_validation < 1) {
			$this->data_array = array(
				'product_name'              => $this->getProductName(),
				'product_short_description' => $this->getProductShortDescription(),
				'product_description'       => $this->getProductDescription(),
				'product_price'             => $this->getProductPrice(),
				'product_quantity'          => $this->getProductQuantity(),
				'category_id'               => $this->getCategoryId(),
				'published'                 => $this->getPublished(),
				'featured'                  => $this->getFeatured()
			);
			if ($mode == 'new')
				$this->data_array['create_time'] = $this->getCreateTime();
			if (!empty($this->getProductImage()['name']))
				$this->data_array['product_image'] = $this->getProductImage()['name'];

			return true;
		} else return false;
	}

	public function getProductName() {
		return $this->product_name;
	}

	public function setProductName($product_name) {
		$this->product_name = $product_name;
	}

	public function getProductShortDescription() {
		return $this->product_short_description;
	}

	public function setProductShortDescription($product_short_description) {
		$this->product_short_description = $product_short_description;
	}
	
	public function getProductDescription() {
		return $this->product_description;
	}

	public function setProductDescription($product_description) {
		$this->product_description = $product_description;
	}

	public function getProductPrice() {
		return $this->product_price;
	}

	public function setProductPrice($product_price) {
		$this->product_price = $product_price;
	}

	public function getProductQuantity() {
		return $this->product_quantity;
	}

	public function setProductQuantity($product_quantity) {
		$this->product_quantity = $product_quantity;
	}
	
	public function getCategoryId() {
		return $this->category_id;
	}
	
	public function setCategoryId($category_id) {
		$this->category_id = $category_id;
	}

	public function getPublished() {
		return $this->published;
	}

	public function setPublished($published) {
		$this->published = $published;
	}
	
	public function getFeatured() {
		return $this->featured;
	}
	
	public function setFeatured($featured) {
		$this->featured = $featured;
	}

	public function getCreateTime() {
		return $this->create_time;
	}
	
	public function setCreateTime($create_time) {
		$this->create_time = $create_time;
	}
	
	public function getCategoryName() {
		$this->sql = "SELECT category_name FROM categories WHERE category_id= $this->category_id";
		if (!$result = $this->db_obj->GetOne($this->sql)) return false;

		return $result;
	}
}