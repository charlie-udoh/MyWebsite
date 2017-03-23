<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 28/01/2017
 * Time: 18:34
 */
require_once('class_article.php');

class comment extends article {
	private $comment_id;
	private $name;
	private $email;
	private $comment;
	private $block;

	public function __construct() {
		parent::__construct();
		$this->setMainTable('comments');
		$this->setLinkedTable('articles');
		$this->setPk('comment_id');
	}

	public function getCommentId() {
		return $this->comment_id;
	}

	public function setCommentId($comment_id) {
		$this->comment_id = $comment_id;
	}

	public function getAllComments($type = '') {
		$this->sql = "SELECT * FROM $this->main_table WHERE article_id=" . $this->getArticleId();
		if ($type == 'allowed') $this->sql .= " AND block = 0";
		if (!$records = $this->db_obj->GetAll($this->sql)) return false;

		return $records;
	}

	public function insertComment() {
		if (!$this->createData()) return false;

		return true;
	}

	public function updateComment() {
		$this->setId($this->comment_id);
		if (!$this->updateData()) return false;

		return true;
	}

	public function deleteComment() {
		$this->setId($this->comment_id);
		if (!$this->deleteData()) return false;

		return true;
	}
	
	public function validateComment($mode) {
		if ($mode == 'edit') {
			if (empty($this->comment_id) || !is_numeric($this->comment_id)) {
				$this->validate_messages['comment_id'] = 'Comment ID cannot be empty';
				$this->failed_validation++;
			}
		}
		if ($mode == 'new') {
			if ($this->name == '' || is_null($this->name)) {
				$this->validate_messages['name'] = 'Please enter your name';
				$this->failed_validation++;
			}
			if ($this->email == '' || is_null($this->email)) {
				$this->validate_messages['email'] = 'Please enter your email';
				$this->failed_validation++;
			}
			if ($this->comment == '' || is_null($this->comment)) {
				$this->validate_messages['comment'] = 'Please enter your comment';
				$this->failed_validation++;
			}
			if (empty($this->getArticleId()) || !is_numeric($this->getArticleId())) {
				$this->validate_messages['article_id'] = 'Article ID cannot be empty';
				$this->failed_validation++;
			}
		}

		if ($this->failed_validation < 1) {
			if ($mode == 'new') {
				$this->data_array['comment'] = addslashes($this->getComment());
				$this->data_array['name'] = addslashes($this->getName());
				$this->data_array['email'] = addslashes($this->getEmail());
				$this->data_array['article_id'] = addslashes($this->getArticleId());
				$this->data_array['create_time'] = addslashes($this->getCreateTime());
			}
			$this->data_array['block'] = $this->getBlock();

			return true;
		} else return false;
	}

	public function getComment() {
		return $this->comment;
	}

	public function setComment($comment) {
		$this->comment = $comment;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getBlock() {
		return $this->block;
	}

	public function setBlock($block) {
		$this->block = $block;
	}
}