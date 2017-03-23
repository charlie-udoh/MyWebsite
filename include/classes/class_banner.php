<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 28/01/2017
 * Time: 18:35
 */
require_once('class_core.php');

class banner extends Core {
	private $banner_id;
	private $banner_description;
	private $banner_image;
	private $published;

	public function __construct() {
		global $db;
		$this->setValidateMessages(array());
		$this->setFailedValidation(0);
		$this->setMainTable('banners');
		$this->setPk('banner_id');
		$this->setDbObj($db);
	}

	public function getBannerId() {
		return $this->banner_id;
	}

	public function setBannerId($banner_id) {
		$this->banner_id = $banner_id;
	}
	
	function getBanner() {
		$this->sql = "SELECT * FROM $this->main_table WHERE $this->pk = $this->banner_id";
		if (!$records = $this->db_obj->GetRow($this->sql)) {
			return false;
		}

		return $records;
	}
	
	public function getAllBanners() {
		$this->sql = "SELECT * FROM $this->main_table WHERE published=1";
		if (!$records = $this->db_obj->GetAll($this->sql)) {
			return false;
		}

		return $records;
	}
	
	public function insertBanner() {
		if (!$this->createData())
			return false;
		else {
			$last_insert_id = $this->db_obj->InsertId();
			$file = $this->getBannerImage();
			//rename uploaded file
			$file['new_name'] = createNewFileName($file['name'], $last_insert_id);
			//update the product image field of the newly inserted record
			$this->sql = "UPDATE $this->main_table SET banner_image= '" . $file['new_name'] . "' WHERE $this->pk= $last_insert_id";
			if (!$this->db_obj->Execute($this->sql)) return false;
			//save image
			if (!saveImage($this->main_table, $file, $last_insert_id)) return false;

			return true;
		}
	}
	
	public function getBannerImage() {
		return $this->banner_image;
	}
	
	public function setBannerImage($banner_image) {
		$this->banner_image = $banner_image;
	}
	
	public function updateBanner() {
		//save image if existing was changed
		if (isset($insert_array['banner_image'])) {
			$file = $this->getBannerImage();
			//rename uploaded file
			$file['new_name'] = createNewFileName($file['name'], $this->banner_id);
			//save image
			if (!saveImage($this->main_table, $file, $this->banner_id)) return false;
			$this->data_array['banner_image'] = $file['new_name'];
		}
		//insert into the database
		$this->setId($this->banner_id);
		if (!$this->updateData()) return false;

		return true;
	}
	
	public function deleteBanner() {
		if (deleteImage($this->main_table, $this->banner_id)) {
			$this->setId($this->banner_id);
			if (!$this->deleteData()) return false;

			return true;
		} else
			return false;
	}
	
	public function validateBanner($mode) {
		if ($mode == 'edit') {
			if (empty($this->banner_id) || !is_numeric($this->banner_id)) {
				$this->validate_messages['banner_id'] = 'Banner ID cannot be empty';
				$this->failed_validation++;
			}
		}
		if ($mode == 'new') {
			if ($this->banner_image['name'] == '' || is_null($this->banner_image['name'])) {
				$this->validate_messages['banner_image'] = 'Please upload a picture';
				$this->failed_validation++;
			} elseif ($this->banner_image['size'] > 2000000) { //file is larger than 5mb
				$this->validate_messages['banner_image'] = 'Image is too large. Please upload images less than 2mb';
				$this->failed_validation++;
			}
		}

		if ($this->failed_validation < 1) {
			$this->data_array = array(
				'banner_description' => $this->getBannerDescription(),
				'published'          => $this->getPublished()
			);
			if (!empty($this->getBannerImage()['name']))
				$this->data_array['banner_image'] = $this->getBannerImage()['name'];

			return true;
		} else return false;
	}
	
	public function getBannerDescription() {
		return $this->banner_description;
	}
	
	public function setBannerDescription($banner_description) {
		$this->banner_description = $banner_description;
	}

	public function getPublished() {
		return $this->published;
	}
	
	public function setPublished($published) {
		$this->published = $published;
	}

}