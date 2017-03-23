<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 05/02/2017
 * Time: 09:21
 */
require_once('../../include/init.php');
$product_obj = new product();
$data = array();
if (isset($_POST['mode'])) {
	$mode = $_POST['mode'];
	if ($mode == 'edit' || $mode == 'new') {
		$product_obj->setProductId($_POST['product_id']);
		$product_obj->setCategoryId($_POST['category']);
		$product_obj->setProductName($_POST['product_name']);
		$product_obj->setProductShortDescription($_POST['product_short_description']);
		$product_obj->setProductDescription($_POST['product_description']);
		$product_obj->setProductPrice($_POST['product_price']);
		$product_obj->setProductQuantity($_POST['product_quantity']);
		$product_obj->setProductImage($_FILES['product_image']);
		$_POST['published'] = isset($_POST['published']) ? 0 : 1;
		$product_obj->setPublished($_POST['published']);
		$_POST['featured'] = isset($_POST['featured']) ? 1 : 0;
		$product_obj->setFeatured($_POST['featured']);

		if ($mode == 'new') {
			$product_obj->setCreateTime(date('Y-m-d h:s:i'));
		}
		$product_obj->setProductId($_POST['product_id']);
		
		if (!$is_validated = $product_obj->validateProduct($mode)) {
			$error_messages = $product_obj->getValidateMessages();
			$data['success'] = false;
			$data['errors'] = $error_messages;
		} else {
			if ($mode == 'new') {
				if (!$product_obj->insertProduct()) {
					$data['message'] = "New record was not created successfully";
					$data['success'] = false;
				} else {
					$data['message'] = "New record has been created successfully";
					$data['success'] = true;
				}
				
			} elseif ($mode == 'edit') {
				if (!$product_obj->updateProduct()) {
					$data['message'] = "Record was not updated successfully";
					$data['success'] = false;
				} else {
					$data['message'] = "Record has been updated successfully";
					$data['success'] = true;
				}
			}
		}
	} elseif ($mode == 'delete') {
		$product_obj->setProductId($_POST['product_id']);
		if (!$product_obj->deleteProduct()) {
			$data['message'] = "Record was not deleted successfully";
			$data['success'] = false;
		} else {
			$data['message'] = "Record has been deleted successfully";
			$data['success'] = true;
		}
	} else {
		$data['message'] = "An error occurred. Mode is not set";
		$data['success'] = false;
	}
	
	echo json_encode($data);
}