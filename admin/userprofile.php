<?php
require_once('../include/config.php');
require_once('../include/init.php');
include_once('../include/classes/class_auth.php');
$auth_obj = new auth();

?>

<?php include('inc/header.php'); ?>

<?php
$page = '';
include('inc/nav.php');
$user_data = array();
$user_data['user_id'] = $user_data['username'] = $user_data['password'] = $user_data['first_name'] = $user_data['last_name'] = $user_data['phone'] = $user_data['email'] = $user_data['address'] = $user_data['facebook_page'] = '';
$user_data = $auth_obj->getUserProfile($_SESSION['userid']);

?>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				User Profile
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-6">
						<div id="message"></div>
						<form role="form" method="post" id="user_form" name="user_form" action="">
							<div class="form-group" id="first_name_group">
								<label>First Name</label>
								<input class="form-control" id="first_name" name="first_name"
								       placeholder="Enter first name" value="<?php echo $user_data['first_name']; ?>">
							</div>
							<div class="form-group" id="last_name_group">
								<label>Last Name</label>
								<input class="form-control" id="last_name" name="last_name"
								       placeholder="Enter last name" value="<?php echo $user_data['last_name']; ?>">
							</div>
							<div class="form-group" id="phone_group">
								<label>Phone Number</label>
								<input class="form-control" id="phone" name="phone" placeholder="Enter Phone number"
								       value="<?php echo $user_data['phone']; ?>">
							</div>
							<div class="form-group" id="email_group">
								<label>Email</label>
								<input type="email" class="form-control" id="email" name="email"
								       placeholder="Enter email" value="<?php echo $user_data['email']; ?>">
							</div>
							<div class="form-group" id="address_group">
								<label>Address</label>
								<textarea id="address" name="address" class="form-control"
								          rows="3"><?php echo $user_data['address']; ?></textarea>
							</div>
							<div class="form-group" id="facebook_group">
								<label>Facebook page</label>
								<input type="text" class="form-control" id="facebook_page" name="facebook_page"
								       placeholder="Enter facebook page"
								       value="<?php echo $user_data['facebook_page']; ?>">
							</div>
							<button type="submit" class="btn btn-primary pull-right" id="submit" name="submit"><i
									class="fa fa-save"></i>Save
							</button>
							<button type="reset" class="btn btn-default" id="reset" name="reset">Reset</button>


						</form>
					</div>
					<!-- /.col-lg-6 (nested) -->
				</div>
				<!-- /.row (nested) -->
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-lg-12 -->
</div>
<?php include('inc/footer.php'); ?>

<!-- Custom Js -->
<script src="../assets/admin/js/custom-scripts.js"></script>
<script>

	$('#category_form').submit(function (event) {
		$('.form-group').removeClass('has-error'); // remove the error class
		$('.help-block').remove(); // remove the error text
		$('#message').empty();  //empty the display message div
		var published = "on";
		if (document.getElementById("published").checked) {
			published = "off";
		}
		// get the form data
		var formData = {
			'first_name'   : $('#first_name').val(),
			'last_name'    : $('#last_name').val(),
			'phone'        : $('#phone').val(),
			'email'        : $('#email').val(),
			'address'      : $('#address').val(),
			'facebook_page': $('#facebook_page').val()
		};
		// process the form
		$.ajax({
				type    : 'POST',
				url     : 'lib/set_userprofile.php',
				data    : formData,
				dataType: 'json',
				encode  : true
			})
			// using the done promise callback
			.done(function (data) {
				if (!data.success) {
					if (data.message) {
						$('#message').append('<div class="alert alert-danger" ><buttton class="close" data-dismiss="alert"><i class= "fa-fw fa fa-times"></i></buttton><i class = "fa-fw fa fa-times"></i>' + data.message + '</div>');
					}
					if (data.errors.category_id) {
						$('#message').append('<div class="help-block">' + data.errors.category_id + '</div>');
					}
					if (data.errors.category_name) {
						$('#cat_name_group').addClass('has-error').append('<div class="help-block">' + data.errors.category_name + '</div>');
					}
					if (data.errors.category_description) {
						$('#cat_description_group').addClass('has-error').append('<div class="help-block">' + data.errors.category_description + '</div>');
					}
					if (data.errors.published) {
						$('#published_group').addClass('has-error').append('<div class="help-block">' + data.errors.published + '</div>');
					}
					if (data.errors.userid) {
						$('#message').append('<div class="help-block">' + data.errors.user_id + '</div>');
					}
				}
				else {
					$('#message').append('<div class="alert alert-success" ><buttton class="close" data-dismiss="alert"><i class= "fa-fw fa fa-times"></i></buttton><i class = "fa-fw fa fa-times"></i>' + data.message + '</div>');
				}
			});
		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});
</script>

</body>
</html>
