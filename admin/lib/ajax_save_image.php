<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 05/04/2017
 * Time: 22:39
 */

/*******************************************************
 * Only these origins will be allowed to upload images *
 ******************************************************/
$accepted_origins = array("http://localhost", "http://192.168.1.1", "http://example.com");

/*********************************************
 * Change this line to set the upload folder *
 *********************************************/
$dir = dirname(__FILE__);
$file_dir = $dir . '/uploads/images/';
//if (!is_dir($file_dir)) {
//    //echo $file_dir;
//    mkdir($file_dir, 0777, true);
//}
//$file_name = basename($_FILES["loaded_file"]["name"]);

reset ($_FILES);
$temp = current($_FILES);
if (is_uploaded_file($temp['tmp_name'])){
	if (isset($_SERVER['HTTP_ORIGIN'])) {
		// same-origin requests won't set an origin. If the origin is set, it must be valid.
		if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
			header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
		} else {
			header("HTTP/1.0 403 Origin Denied");
			return;
		}
	}

	/*
	  If your script needs to receive cookies, set images_upload_credentials : true in
	  the configuration and enable the following two headers.
	*/
	// header('Access-Control-Allow-Credentials: true');
	// header('P3P: CP="There is no P3P policy."');

	// Sanitize input
	if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
		header("HTTP/1.0 500 Invalid file name.");
		return;
	}

	// Verify extension
	if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png","jpeg"))) {
		header("HTTP/1.0 500 Invalid extension.");
		return;
	}

	// Accept upload if there was no origin, or if it is an accepted origin
	$file_to_upload = $file_dir . $temp['name'];

	move_uploaded_file($temp['tmp_name'], $file_to_upload);
	echo json_encode(array('location' => $file_to_upload));
	// Respond to the successful upload with JSON.
	// Use a location key to specify the path to the saved image resource.
	// { location : '/your/uploaded/image/file'}
} else {
	// Notify editor that the upload failed
	header("HTTP/1.0 500 Server Error");
}