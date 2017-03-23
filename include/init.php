<?php
//require the config file
require_once('config.php');

//start session if not started already
if (session_id() == '')
	session_start();

//load helper functions
require_once('helper_functions.php');
//load all classes
require_once('database/mysql_database.php');
require_once('classes/class_auth.php');
require_once('classes/class_category.php');
require_once('classes/class_article.php');
require_once('classes/class_comment.php');
require_once('classes/class_product.php');
require_once('classes/class_order.php');
require_once('classes/class_banner.php');

//instantiate the data access class
$db = new MysqlDatabase();

//fetch the admin data to be used across all pages
$auth = new Auth();
$user = $auth->getUserProfile();