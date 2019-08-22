<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 12/02/2017
 * Time: 12:22
 */
require_once('../include/init.php');
$msg = '';
$username = '';
$password = '';
if (isset($_POST['submit'])) {
	$auth->setUsername($_POST['username']);
	$auth->setPassword($_POST['password']);
	if (!$auth->validateLogin()) {
		$msg = $auth->errors;
	} else {
		if ($auth->login()) {
			header("location: index.php");
		} else {
			$msg = $auth->errors;
		}
	}
}

?>


<!--Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
	<title>My Admin | Login</title>
	<!-- Custom Theme files -->
	<link href="../assets/admin/css/login_style.css" rel="stylesheet" type="text/css" media="all"/>
	<!-- Custom Theme files -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="keywords"
	      content="Ensaluto Form Responsive,Login form web template, Sign up Web Templates, Flat Web Templates, Login signup Responsive web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design"/>
	<!--Google Fonts-->
	<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>-->
	<!--Google Fonts-->
	<script src="../assets/admin/js/jquery-2.1.1.min.js"></script>
</head>
<body>
	<!--sign up form start here-->
	<div class="app">

		<div class="top-bar">
			<h2>Welcome to MyAdmin</h2>
		</div>

		<form id="login_form" method="post" action="login.php" style="background-color: #fff;">
			<br><br>
			<div id="message" style="color: red;">
				<?php echo $msg; ?>
			</div>
			<br><br>
			<input type="text" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>"
			       placeholder="Enter username" name="username" id="password">
			<input type="password" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>"
			       placeholder="Enter Password" name="password" id="password">
			<input type="submit" value="Login" name="submit" id="submit"/>
			<br><br><br><br>
			<div id="">
				<a href="../index.php"> Back to Website</a>
			</div>
		</form>
	</div>
</body>
</html>
