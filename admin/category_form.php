<?php
require_once('../include/init.php');
if (!$auth->isLoggedIn()) {
	header("location: login.php");
}
$category_obj = new category();

?>

<?php include('inc/header.php'); ?>

<?php
$page = 'categories';
include('inc/nav.php');
$cat_data = array();
$cat_data['category_id'] = $cat_data['category_name'] = $cat_data['category_description'] = $cat_data['published'] = '';
$mode = '';
if (isset($_GET['mode'])) {
	if ($_GET['mode'] == 'new') $mode = 'new';
	else $mode = 'edit';
	if ($mode == 'edit') {
		$category_obj->setCategoryId($_GET['id']);
		$cat_data = $category_obj->getCategory();
	}
}
?>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				New Category
				<a href="categories.php" class="pull-right">
					Back to Category List
				</a>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-6">
						<div id="message"></div>
						<form role="form" method="post" id="category_form" name="category_form" action="">
							<div class="form-group" id="cat_name_group">
								<label>Name</label>
								<input class="form-control" id="category_name" name="category_name"
								       placeholder="Enter name" value="<?php echo $cat_data['category_name']; ?>">
							</div>

							<div class="form-group" id="cat_description_group">
								<label>Description</label>
								<textarea id="category_description" name="category_description" class="form-control"
								          rows="3"><?php echo $cat_data['category_description']; ?></textarea>
							</div>
							<div class="form-group" id="published_group">
								<label>Disabled</label>
								<div class="checkbox">
									<label>
										<input type="checkbox" id="published" name="published"
										       value="" <?php if (!$cat_data['published']) echo 'checked'; ?>>Disabled
									</label>
								</div>
							</div>
							<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
							<input type="hidden" name="category_id" id="category_id"
							       value="<?php echo $cat_data['category_id']; ?>">
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
			'category_id'         : $('#category_id').val(),
			'category_name'       : $('#category_name').val(),
			'category_description': $('#category_description').val(),
			'mode'                : $('#mode').val(),
			'published'           : published
		};
		// process the form
		$.ajax({
				type    : 'POST',
				url     : 'lib/set_category.php',
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
