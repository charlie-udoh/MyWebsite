<?php
require_once('../include/init.php');
if (!$auth->isLoggedIn()) {
	header("location: login.php");
}
$banner_obj = new banner();
$banner_data = array();
$banner_data['banner_id'] = $banner_data['banner_image'] = $banner_data['banner_description'] = $banner_data['published'] = $banner_data['banner_image_link'] = '';
$mode = '';
if (isset($_GET['mode'])) {
	if ($_GET['mode'] == 'new') $mode = 'new';
	else $mode = 'edit';
	if ($mode == 'edit') {
		$banner_obj->setBannerId($_GET['id']);
		$banner_data = $banner_obj->getBanner();
		$banner_data['banner_image_link'] = "../images/banners/" . $banner_data['banner_id'] . '/' . $banner_data['banner_image'];
	}
}
?>

<?php
$page = 'banners';
include('inc/nav.php');
?>
<?php include('inc/header.php'); ?>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<?php echo ucfirst($mode); ?> Banner
				<a href="banners.php" class="pull-right">
					Back to Banner List
				</a>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-9">
						<div id="message"></div>
						<form role="form" method="post" id="banner_form" name="banner_form" action=""
						      enctype="multipart/form-data">
							<div class="form-group" id="banner_image_group">
								<label>Upload Picture</label>
								<input type='file' id="banner_image" name="banner_image"><br>
							</div>
							<div>
								<img id="img_preview" src="<?php echo $banner_data['banner_image_link'] ?>" height="300"
								     width="770" alt="your image"/>
							</div>
							<br>
							<div class="form-group" id="banner_description_group">
								<label>Description</label>
								<textarea class="form-control" id="banner_description" name="banner_description"
								          placeholder="Enter description text"
								          rows="3"><?php echo $banner_data['banner_description'] ?></textarea>
							</div>
							<div class="form-group" id="published_group">
								<div class="checkbox">
									<label>
										<input type="checkbox" id="published" name="published"
										       value="" <?php if ($mode == 'edit' && !$banner_data['published']) echo 'checked'; ?>>Disabled
									</label>
								</div>
							</div>
							<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
							<input type="hidden" name="banner_id" id="banner_id"
							       value="<?php echo $banner_data['banner_id']; ?>">
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
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#img_preview').attr('src', e.target.result);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}

	$('#banner_form').submit(function (event) {
		$('.form-group').removeClass('has-error'); // remove the error class
		$('.help-block').remove(); // remove the error text
		$('#message').empty();  //empty the display message div
		var published = "on";
		if (document.getElementById("published").checked) {
			published = "off";
		}

		var formData = new FormData($(this)[0]);
		// process the form
		$.ajax({
				type       : 'POST',
				url        : 'lib/set_banner.php',
				data       : formData,
				dataType   : 'json',
				cache      : false,
				contentType: false,
				processData: false
			})
			.done(function (data) {
				if (!data.success) {
					if (data.message) {
						$('#message').append('<div class="alert alert-danger" ><buttton class="close" data-dismiss="alert"><i class= "fa-fw fa fa-times"></i></buttton><i class = "fa-fw fa fa-times"></i>' + data.message + '</div>');
					}
					if (data.errors.banner_id) {
						$('#message').append('<div class="help-block">' + data.errors.banner_id + '</div>');
					}
					if (data.errors.banner_image) {
						$('#banner_image_group').addClass('has-error').append('<div class="help-block">' + data.errors.banner_image + '</div>');
					}
					if (data.errors.banner_description) {
						$('#banner_description_group').addClass('has-error').append('<div class="help-block">' + data.errors.banner_description + '</div>');
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

	$("#banner_image").change(function () {
		readURL(this);
	});
</script>

</body>
</html>
