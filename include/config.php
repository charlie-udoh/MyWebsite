<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 28/01/2017
 * Time: 18:03
 */

$connection = array(
	'mysql' => [
		'driver'      => 'mysql', //database type
		'host'        => 'localhost', //database host name, on local server it is 'localhost'
		'database'    => 'mywebsite', // database name
		'username'    => 'root',  // database username
		'password'    => 'nolongthing',  // user password
		'persistency' => TRUE  // persistency
	]
);

$email = array(
	"production"  => [
		'host'     => "just45.justhost.com",
		'username' => "automailer@dqdemos.com",
		'password' => "Test123*#",
		'port'     => "465"
	],
	"development" => [
		'host'     => "secure368.sgcpanel.com",
		'username' => "automailer@oandsonsnetwork.com",
		'password' => "automailer*123#",
		'port'     => "465"
	]
);
// set a database connection as default
// assign the name defined in connection variable
$database = 'mysql';

//set the smtp configuration to use
$smtp = 'development';

//$root= 'http://localhost/mywebsite1';
//define project root
$directory = str_replace('include', '', realpath(dirname(__FILE__)));
$document_root = realpath($_SERVER['DOCUMENT_ROOT']);
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' .
	$_SERVER['HTTP_HOST'];
if (strpos($directory, $document_root) === 0) {
	$base_url .= str_replace(DIRECTORY_SEPARATOR, '/', substr($directory, strlen($document_root)));
}

// define root folder
define('ROOT', $directory);
define('BASE_URL', $base_url);

// define database
$database_config = $connection[$database];
$driver = $database_config['driver'];
define('DATABASE', ucfirst($driver) . 'Database');
define('DATABASE_NAME', $database_config['database']);
define('DATABASE_USER', $database_config['username']);
define('DATABASE_PASS', $database_config['password']);
define('DATABASE_SERVER', $database_config['host']);
define('PDO_DSN', 'mysql:host=' . DATABASE_SERVER . ';dbname=' . DATABASE_NAME);
define('DB_PERSISTENCY', $database_config['persistency']);

// define smtp
$smtp_config = $email[$smtp];
define('SMTP_HOST', $smtp_config['host']);
define('SMTP_USER', $smtp_config['username']);
define('SMTP_PASS', $smtp_config['password']);
define('SMTP_PORT', $smtp_config['port']);

//set default timezone
define('DEFAULT_TIMEZONE', "Africa/Lagos");

define('IMAGES_DIR', 'images' . DIRECTORY_SEPARATOR);
//define('ADMIN_DIR', VIEWS_DIR. 'admin/');
//define('SITE_DIR', VIEWS_DIR. 'site/');
//define('ERROR_DIR', VIEWS_DIR. 'errors/');

//define error display
define('SHOW_ERROR', TRUE);

if (SHOW_ERROR) {
	ini_set('display_errors', '1');
} else {
	ini_set('display_errors', '0');
}