<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 05/02/2017
 * Time: 09:21
 */
require_once('../../include/init.php');
$article_obj = new article();
$data = array();
if (isset($_POST['mode'])) {
	$mode = $_POST['mode'];
	if ($mode == 'edit' || $mode == 'new') {
		$article_obj->setArticleId($_POST['article_id']);
		$article_obj->setCategoryId($_POST['category']);
		$article_obj->setArticleTitle($_POST['article_title']);
		$article_obj->setArticleContent($_POST['article_content']);
		$_POST['product'] = ($_POST['product'] != '') ? $_POST['product'] : 0;
		$article_obj->setProductId($_POST['product']);
		$article_obj->setCreatedBy(1);
		$article_obj->setArticleImage(isset($_FILES['article_image']) ? $_FILES['article_image'] : array());
		$_POST['published'] = isset($_POST['published']) ? 0 : 1;
		$article_obj->setPublished($_POST['published']);
		$_POST['featured'] = isset($_POST['featured']) ? 1 : 0;
		$article_obj->setFeatured($_POST['featured']);
		$_POST['allow_comments'] = isset($_POST['allow_comments']) ? 1 : 0;
		$article_obj->setAllowComments($_POST['allow_comments']);
		
		if ($mode == 'new') {
			$article_obj->setCreateTime(date('Y-m-d h:s:i'));
		}
		$article_obj->setArticleId($_POST['article_id']);
		
		if (!$is_validated = $article_obj->validateArticle($mode)) {
			$error_messages = $article_obj->getValidateMessages();
			$data['success'] = false;
			$data['errors'] = $error_messages;
		} else {
			if ($mode == 'new') {
				if (!$article_obj->insertArticle()) {
					$data['message'] = "New record was not created successfully";
					$data['success'] = false;
				} else {
					$data['message'] = "New record has been created successfully";
					$data['success'] = true;
				}
				
			} elseif ($mode == 'edit') {
				if (!$article_obj->updateArticle()) {
					$data['message'] = "Record was not updated successfully";
					$data['success'] = false;
				} else {
					$data['message'] = "Record has been updated successfully";
					$data['success'] = true;
				}
			}
		}
	} elseif ($mode == 'delete') {
		$article_obj->setArticleId($_POST['article_id']);
		if (!$article_obj->deleteArticle()) {
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