<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 28/01/2017
 * Time: 18:35
 */
require_once('class_core.php');

class order extends Core {
	private $order_id;
	private $email;
	private $phone;
	private $address;
	private $quantity;
	private $product_id;
	private $status;
	private $create_time;

	public function __construct() {
		global $db;
		$this->setValidateMessages(array());
		$this->setFailedValidation(0);
		$this->setMainTable('orders');
		$this->setPk('order_id');
		$this->setDbObj($db);
	}

	public function getOrderId() {
		return $this->order_id;
	}

	public function setOrderId($order_id) {
		$this->order_id = $order_id;
	}

	public function getOrder() {
		global $db;
		$this->sql = "SELECT * FROM $this->main_table WHERE $this->pk = $this->order_id";
		if (!$records = $db->GetRow($this->sql)) {
			return false;
		}

		return $records;
	}

	public function getAllOrders() {
		global $db;
		$this->sql = "SELECT * FROM $this->main_table WHERE published=1";
		if (!$records = $db->GetAll($this->sql)) {
			return false;
		}

		return $records;
	}

	public function insertOrder() {
		if (!$this->createData()) return false;

		return true;
	}

	public function updateOrder() {
		$this->setId($this->order_id);
		if (!$this->updateData()) return false;

		return true;
	}

	public function deleteOrder() {
		$this->setId($this->order_id);
		if (!$this->deleteData()) return false;

		return true;
	}

	public function validateOrder($mode) {
		if ($mode == 'new') {
			if (empty($this->product_id) || !is_numeric($this->product_id)) {
				$this->validate_messages['message'] = 'an error has occurred with this product';
				$this->failed_validation++;
			}
			if ($this->email == '' && $this->phone == '') {
				$this->validate_messages['message'] = 'Please provide your phone number or email';
				$this->failed_validation++;
			}
			if ($this->quantity < 1) {
				$this->validate_messages['message'] = 'Please input quantity';
				$this->failed_validation++;
			}
		}
		if ($this->failed_validation < 1) {
			if ($mode == 'new') {
				$this->data_array['email'] = addslashes($this->getEmail());
				$this->data_array['phone'] = addslashes($this->getPhone());
				$this->data_array['address'] = addslashes($this->getAddress());
				$this->data_array['quantity'] = $this->getQuantity();
				$this->data_array['product_id'] = $this->getProductId();
				$this->data_array['create_time'] = $this->getCreateTime();
			}
			$this->data_array['status'] = $this->getStatus();

			return true;
		} else return false;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getPhone() {
		return $this->phone;
	}

	public function setPhone($phone) {
		$this->phone = $phone;
	}

	public function getAddress() {
		return $this->address;
	}

	public function setAddress($address) {
		$this->address = $address;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	public function getProductId() {
		return $this->product_id;
	}

	public function setProductId($product_id) {
		$this->product_id = $product_id;
	}
	
	public function getCreateTime() {
		return $this->create_time;
	}

	public function setCreateTime($create_time) {
		$this->create_time = $create_time;
	}

	public function getStatus() {
		return $this->status;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function getProductName() {
		$this->sql = "SELECT product_name FROM products WHERE product_id= $this->product_id";
		if (!$result = $this->db_obj->GetOne($this->sql)) return false;

		return $result;
	}
}