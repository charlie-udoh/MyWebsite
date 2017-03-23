<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 05/02/2017
 * Time: 09:21
 */
require_once('../../include/init.php');
$banner_obj = new banner();
$data = array();
if (isset($_POST['mode'])) {
	$mode = $_POST['mode'];
	if ($mode == 'edit' || $mode == 'new') {
		$banner_obj->setBannerId($_POST['banner_id']);
		$banner_obj->setBannerImage($_FILES["banner_image"]);
		$banner_obj->setBannerDescription($_POST['banner_description']);
		$_POST['published'] = isset($_POST['published']) ? 0 : 1;
		$banner_obj->setPublished($_POST['published']);
		if (!$is_validated = $banner_obj->validateBanner($mode)) {
			$error_messages = $banner_obj->getValidateMessages();
			$data['success'] = false;
			$data['errors'] = $error_messages;
		} else {
			if ($mode == 'new') {
				if (!$banner_obj->insertBanner()) {
					$data['message'] = "New record was not created successfully";
					$data['success'] = false;
				} else {
					$data['message'] = "New record has been created successfully";
					$data['success'] = true;
				}
				
			} elseif ($mode == 'edit') {
				if (!$banner_obj->updateBanner()) {
					$data['message'] = "Record was not updated successfully";
					$data['success'] = false;
				} else {
					$data['message'] = "Record has been updated successfully";
					$data['success'] = true;
				}
			}
		}
	} elseif ($mode == 'delete') {
		$banner_obj->setBannerId($_POST['id']);
		if (!$banner_obj->deleteBanner()) {
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