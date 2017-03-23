<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 05/02/2017
 * Time: 09:21
 */
require_once('../../include/config.php');
require_once('../../include/init.php');
require_once('../../include/classes/class_category.php');
$category_obj = new category();
$data = array();
if (isset($_POST['mode'])) {
	$mode = $_POST['mode'];
	if ($mode == 'edit' || $mode == 'new') {
		$category_obj->setCategoryId($_POST['category_id']);
		$category_obj->setCategoryName($_POST['category_name']);
		$category_obj->setCategoryDescription($_POST['category_description']);
		if ($_POST['published'] == 'off') $_POST['published'] = 0;
		else  $_POST['published'] = 1;
		$category_obj->setPublished($_POST['published']);
		$category_obj->setCreateTime(date('Y-m-d h:i:s'));
		$category_obj->setCreatedBy(1);
		if (!$is_validated = $category_obj->validateCategory($mode)) {
			$error_messages = $category_obj->getValidateMessages();
			$data['success'] = false;
			$data['errors'] = $error_messages;
		} else {
			if ($mode == 'new') {
				if (!$category_obj->insertCategory()) {
					$data['message'] = "New record was not created successfully";
					$data['success'] = false;
				} else {
					$data['message'] = "New record has been created successfully";
					$data['success'] = true;
				}

			} elseif ($mode == 'edit') {
				if (!$category_obj->updateCategory()) {
					$data['message'] = "Record was not updated successfully";
					$data['success'] = false;
				} else {
					$data['message'] = "Record has been updated successfully";
					$data['success'] = true;
				}
			}
		}
	} elseif ($mode == 'delete') {
		$category_obj->setCategoryId($_POST['id']);
		if (!$category_obj->deleteCategory()) {
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