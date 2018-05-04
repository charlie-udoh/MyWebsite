<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 28/01/2017
 * Time: 18:34
 */
require_once('class_core.php');

class article extends Core {
	private $article_id;
	private $article_title;
	private $article_content;
	private $article_image;
	private $product_id;
	private $category_id;
	private $allow_comments;
	private $published;
	private $featured;
	private $created_by;
	private $create_time;
	
	public function __construct() {
		global $db;
		$this->setValidateMessages(array());
		$this->setFailedValidation(0);
		$this->setMainTable('articles');
		$this->setPk('article_id');
		$this->setDbObj($db);
	}
	
	public function getArticleId() {
		return $this->article_id;
	}

	public function setArticleId($article_id) {
		$this->article_id = $article_id;
	}

	public function getArticleImage() {
		return $this->article_image;
	}

	public function setArticleImage($article_image) {
		$this->article_image = $article_image;
	}

	public function getArticleTitle() {
		return $this->article_title;
	}

	public function setArticleTitle($article_title) {
		$this->article_title = $article_title;
	}

	public function getArticleContent() {
		return $this->article_content;
	}

	public function setArticleContent($article_content) {
		$this->article_content = $article_content;
	}

	public function getCategoryId() {
		return $this->category_id;
	}

	public function setCategoryId($category_id) {
		$this->category_id = $category_id;
	}

	public function getProductId() {
		return $this->product_id;
	}

	public function setProductId($product_id) {
		$this->product_id = $product_id;
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

	public function getAllowComments() {
		return $this->allow_comments;
	}

	public function setAllowComments($allow_comments) {
		$this->allow_comments = $allow_comments;
	}

	public function getCreatedBy() {
		return $this->created_by;
	}

	public function setCreatedBy($created_by) {
		$this->created_by = $created_by;
	}

	public function getCreateTime() {
		return $this->create_time;
	}

	public function setCreateTime($create_time) {
		$this->create_time = $create_time;
	}

	public function getArticle() {
		$this->sql = "SELECT * FROM $this->main_table WHERE article_title= '$this->article_title' OR article_id= '$this->article_id'";
		if (!$records = $this->db_obj->GetRow($this->sql)) return false;
		$records['category_name'] = $this->getCategoryName($records['category_id']);

		return $records;
	}

	public function getCategoryName($id) {
		$this->sql = "SELECT category_name FROM categories WHERE category_id= $id";
		$result = $this->db_obj->GetOne($this->sql);

		return $result;
	}
	
	public function getAllArticles($type = '') {
		$this->sql = "SELECT art.*, cat.category_name FROM $this->main_table art INNER JOIN categories cat ON cat.category_id= art.category_id WHERE cat.published=1 AND art.published=1";
		if ($type == 'featured') $this->sql .= ' AND featured= 1';
		if ($type == 'category') $this->sql .= " AND art.category_id= '" . $this->category_id . "'";
		if ($type == 'search') $this->sql .= " AND (article_title LIKE '%" . $this->search_string . "%' OR article_content LIKE '%" . $this->search_string . "%' OR category_name LIKE '%" . $this->search_string . "%')";
		if (!$records = $this->db_obj->GetAll($this->sql)) return false;
		//for ($i=0; $i < count($records); $i++) {
		//	$records[$i]['category_name']= $this->getCategoryName($records[$i]['category_id']);
		//}
		return $records;
	}
	
	public function insertArticle() {
		if (!$this->createData())
			return false;
		else {
			$last_insert_id = $this->db_obj->InsertId();
			$file = $this->getArticleImage();
			//rename uploaded file
			$file['new_name'] = createNewFileName($file['name'], $last_insert_id);
			//update the product image field of the newly inserted record
			$this->sql = "UPDATE $this->main_table SET article_image= '" . $file['new_name'] . "' WHERE $this->pk= $last_insert_id";
			if (!$this->db_obj->Execute($this->sql)) return false;
			//save image
			if (!saveImage($this->main_table, $file, $last_insert_id)) return false;

			return true;
		}
	}

	public function updateArticle() {
		//save image if existing was changed
		if (isset($this->data_array['article_image'])) {
			$file = $this->getArticleImage();
			//rename uploaded file
			$file['new_name'] = createNewFileName($file['name'], $this->article_id);
			//save image
			if (!saveImage($this->main_table, $file, $this->article_id)) return false;
			$this->data_array['article_image'] = $file['new_name'];
		}
		//insert into the database
		$this->setId($this->article_id);
		if (!$this->updateData()) return false;

		return true;
	}
	
	public function deleteArticle() {
		if (deleteImage($this->main_table, $this->article_id)) {
			$this->setId($this->article_id);
			if (!$this->deleteData()) return false;

			return true;
		} else
			return false;
	}

	public function validateArticle($mode) {
		if ($mode == 'edit') {
			if (empty($this->article_id) || !is_numeric($this->article_id)) {
				$this->validate_messages['article_id'] = 'Banner ID cannot be empty';
				$this->failed_validation++;
			}
		}
		if ($mode == 'new') {
			if (empty($this->article_image)) {
				$this->validate_messages['article_image'] = 'Please upload a picture';
				$this->failed_validation++;
			} elseif ($this->article_image['size'] > 2000000) {
				$this->validate_messages['article_image'] = 'Image is too large. Please upload images less than 2mb';
				$this->failed_validation++;
			}
		}
		if (empty($this->category_id) || !is_numeric($this->category_id)) {
			$this->validate_messages['category'] = 'Please select a category';
			$this->failed_validation++;
		}
		if (empty($this->article_content) || is_null($this->article_content)) {
			$this->validate_messages['article_content'] = 'Please input content';
			$this->failed_validation++;
		}
		if (empty($this->article_title) || is_null($this->article_title)) {
			$this->validate_messages['article_title'] = 'Please type in title';
			$this->failed_validation++;
		}

		if ($this->failed_validation < 1) {
			$this->data_array = array(
				'article_title'   => $this->getArticleTitle(),
				'article_content' => addslashes($this->getArticleContent()),
				'category_id'     => $this->getCategoryId(),
				'product_id'      => $this->getProductId(),
				'published'       => $this->getPublished(),
				'featured'        => $this->getFeatured(),
				'allow_comments'  => $this->getAllowComments(),
				'created_by'      => $this->getCreatedBy()
			);
			if ($mode == 'new')
				$this->data_array['create_time'] = $this->getCreateTime();
			if (!empty($this->getArticleImage()['name']))
				$this->data_array['article_image'] = $this->getArticleImage()['name'];

			return true;
		} else return false;
	}

	public function getArticleCount() {
		$rs = array();
		$this->sql = "SELECT category_name, count(article_id) AS count FROM articles INNER JOIN categories  ON categories.category_id= articles.category_id GROUP BY category_name";
		if (!$result = $this->db_obj->GetAll($this->sql)) {
			return false;
		}
		foreach ($result as $count) {
			$rs[$count['category_name']] = $count['count'];
		}

		return $rs;
	}

	public function getCommentCount($id) {
		$this->sql = "SELECT count(comment_id) FROM comments WHERE article_id= $id";
		$result = $this->db_obj->GetOne($this->sql);

		return $result;
	}
}