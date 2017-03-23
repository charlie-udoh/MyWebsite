<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 05/02/2017
 * Time: 09:21
 */
require_once('../../include/init.php');
$comment_obj = new comment();
$data = array();
if (isset($_POST['mode'])) {
	$mode = $_POST['mode'];
	if ($mode == 'edit' || $mode == 'new') {
		if ($mode == 'new') {
			$comment_obj->setName($_POST["name"]);
			$comment_obj->setEmail($_POST['email']);
			$comment_obj->setComment($_POST['comment']);
			$comment_obj->setArticleId($_POST['article_id']);
			$comment_obj->setCreateTime(date('Y-m-d h:i:s'));
		}
		if ($mode == 'edit') {
			$comment_obj->setcommentId($_POST['comment_id']);
		}
		$comment_obj->setBlock($_POST['block']);

		if (!$is_validated = $comment_obj->validatecomment($mode)) {
			$error_messages = $comment_obj->getValidateMessages();
			$data['success'] = false;
			$data['errors'] = $error_messages;
		} else {
			if ($mode == 'new') {
				if (!$comment_obj->insertcomment()) {
					$data['message'] = "New record was not created successfully";
					$data['success'] = false;
				} else {
					$data['message'] = "New record has been created successfully";
					$data['success'] = true;
				}
				
			} elseif ($mode == 'edit') {
				if (!$comment_obj->updatecomment()) {
					$data['message'] = "Record was not updated successfully";
					$data['success'] = false;
				} else {
					$data['message'] = "Record has been updated successfully";
					$data['success'] = true;
				}
			}
		}
	} elseif ($mode == 'delete') {
		$comment_obj->setcommentId($_POST['id']);
		if (!$comment_obj->deletecomment()) {
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