<?php
require_once('include/init.php');
$msg = '';
$name = $email = $message = "";
if (isset($_POST['submit'])) {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$message = $_POST['message'];
	if ($email == '') {
		$msg = "<p class='alert alert-danger'>Please fill in your email</p>";
	} elseif (trim($message) == '') {
		$msg = "<p class='alert alert-danger'>Please fill in the message box</p>";
	} else {
		$subject = 'NEW MESSAGE ON ELNET';
		$title = 'NEW MESSAGE ON ELNET.COM';
		$email_message = "<div>
							<h4>Sent at @ " . date('d/m/Y h:i a') . "</h4>
							<h5><b>Name: </b> $name</span><br>
							<h5><b>Email: </b>$email</span><br>
							<div><b>Message: </b>$message</span><br>
							</div>";
		if (sendMail($subject, $title, $email_message)) {
			$sql = "INSERT INTO messages(sender, email, message) VALUES ('$name', '$email', '$message')";
			if (!$db->Execute($sql))
				$msg = '<p class=\'alert alert-danger\'>Message could not be sent</p>';
			else {
				$msg = '<p class=\'alert alert-success\'>We have received your message. We will get back to you shortly</p>';
				$name = $email = $message = "";
			}
		} else {
			$msg = '<p class=\'alert alert-danger\'>Message could not be sent</p>';
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/layout/pagehead.php'); ?>
</head>

<body>
	<!-- header -->
	<?php include('include/layout/header.php'); ?>
	<!-- //header -->
	<!-- navigation -->
	<?php include('include/layout/nav.php'); ?>
	<!-- //navigation -->
	<!-- breadcrumbs -->
	<div class="breadcrumbs container">
		<div class="">
			<ol class="breadcrumb breadcrumb1 animated wow slideInLeft" data-wow-delay=".5s">
				<li><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>Home</a></li>
				<li class="active">Contact</li>
			</ol>
		</div>
	</div>
	<!-- //breadcrumbs -->
	<!-- contact -->
	<div class="about">
		<div class="w3_agileits_contact_grids container">
			<div class="col-md-6 w3_agileits_contact_grid_right">
				<h2 class="w3_agile_header">Leave a<span> Message</span></h2>
				<?php echo $msg; ?>
				<form action="" method="post" name="contact_form">
					<div class="form-group row">
						<div class="col-md-2">
							<label>Name: </label>
						</div>
						<div class="col-md-10">
							<input class="form-control" type="text" id="name" name="name" value="<?php echo $name; ?>"
							       placeholder=" "/>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-2">
							<label>Email: </label>
						</div>
						<div class="col-md-10">
							<input class="form-control" type="email" id="input-26" name="email"
							       placeholder=" " <?php echo $email; ?>/>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-2">
							<label>Message: </label>
						</div>
						<div class="col-md-10">
							<textarea class="form-control" name="message" id="message"
							          placeholder="Your message here..." rows="10"><?php echo $message; ?></textarea>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-2">
							<label></label>
						</div>
						<div class="col-md-10">
							<input type="submit" value="Submit" name="submit">
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-6 w3_agileits_contact_grid_left">
				<!--<div class="agile_map">-->
				<!--	<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950.3905851087434!2d-34.90500565012194!3d-8.061582082752993!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x7ab18d90992e4ab%3A0x8e83c4afabe39a3a!2sSport+Club+Do+Recife!5e0!3m2!1sen!2sin!4v1478684415917" style="border:0"></iframe>-->
				<!--</div>-->
				<div class="agileits_w3layouts_map_pos">
					<div class="agileits_w3layouts_map_pos1">
						<h3>Contact Info</h3><br>

						<ul class="wthree_contact_info_address">
							<li><i class="fa fa-envelope" aria-hidden="true"></i><a
									href="<?php echo 'mailto:' . $user['email']; ?>"><?php echo $user['email']; ?></a>
							</li>
							<li><i class="fa fa-phone" aria-hidden="true"></i><?php echo $user['phone']; ?></li>
						</ul>
						<div class="w3_agile_social_icons w3_agile_social_icons_contact">
							<ul>
								<li><a href="#" class="icon icon-cube agile_facebook"></a></li>
								<li><a href="#" class="icon icon-cube agile_rss"></a></li>
								<li><a href="#" class="icon icon-cube agile_t"></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<div class="clearfix"></div>
		</div>
	</div>
	<!-- contact -->
	<!-- //footer -->
	<?php include('include/layout/footer.php'); ?>
	<!-- //footer -->

	<!-- //scripts -->
	<?php include('include/layout/scripts.php'); ?>
	<!-- //scripts -->
</body>
</html>