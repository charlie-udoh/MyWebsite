<?php
require_once('../include/init.php');
if (!$auth->isLoggedIn()) {
	header("location: login.php");
}
$cat_data = array();
$product_data = array();
$article_data = array();
$article_data['article_id'] = $article_data['article_title'] = $article_data['category_id'] = $article_data['product_id'] = $article_data['article_content'] = $article_data['article_image_link'] = '';
$article_data['featured'] = 0;
$article_data['published'] = $article_data['allow_comments'] = 1;
$mode = '';

$category_obj = new category();
$product_obj = new product();
$article_obj = new article();

if (isset($_GET['mode'])) {
	if ($_GET['mode'] == 'new') $mode = 'new';
	else $mode = 'edit';
	if ($mode == 'edit') {
		$article_obj->setArticleId($_GET['id']);
		$article_data = $article_obj->getArticle();
		$article_data['article_image_link'] = "../images/articles/" . $article_data['article_id'] . '/' . $article_data['article_image'];
	}
}

//build category options for select element
$categories = $category_obj->getAllCategories();
$c_options = "<option value=''>--Select a category--</option>";
foreach ($categories as $category) {
	if ($article_data['category_id'] == $category['category_id']) $selected = 'selected';
	else $selected = '';
	$c_options .= "<option value='" . $category['category_id'] . "' $selected>" . $category['category_name'] . "</option>";
}
//build product options for select element
$products = $product_obj->getAllProducts();
$p_options = "<option value=''>--Select a product--</option>";
foreach ($products as $product) {
	if ($article_data['product_id'] == $product['product_id']) $selected = 'selected';
	else $selected = '';
	$p_options .= "<option value='" . $product['product_id'] . "' $selected>" . $product['product_name'] . "</option>";
}
?>

<?php include('inc/header.php'); ?>

<?php
$page = 'articles';
include('inc/nav.php');
?>
<div class="row">
	<div class="col-md-12">
		<!-- Advanced Tables -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<?php echo ucfirst($mode); ?> Article
				<a href="articles.php" class="pull-right">
					Back to Article List
				</a>
			</div>
			<div class="panel-body">
				<div id="message"></div>
				<form role="form" method="post" id="article_form" name="article_form" action=""
				      enctype="multipart/form-data">
					<div class="row">
						<div class="col-lg-8">
							<div id="message"></div>

							<div class="form-group" id="article_title_group">
								<label>Title</label>
								<input class="form-control" id="article_title" name="article_title"
								       placeholder="Enter title of article"
								       value="<?php echo $article_data['article_title']; ?>">
							</div>
							<div class="form-group" id="category_group">
								<label>Category</label>
								<select class="form-control" id="category" name="category">
									<?php echo $c_options; ?>
								</select>
							</div>
							<div class="form-group" id="product_group">
								<label>Product</label>
								<select class="form-control" id="product" name="product">
									<?php echo $p_options; ?>
								</select>
							</div>
							<div class="form-group" id="article_content_group">
								<label for="article_content">Body</label>
								<textarea id="article_content" name="article_content"
								          class="form-control"><?php echo $article_data['article_content']; ?></textarea>
							</div>

							<div class="form-group" id="checkbox_group">
								<label class="checkbox-inline">
									<input type="checkbox" id="allow_comments" name="allow_comments"
									       value="" <?php if ($article_data['allow_comments']) echo 'checked'; ?>>Allow
									comments
								</label>
								<label class="checkbox-inline">
									<input type="checkbox" id="featured" name="featured"
									       value="" <?php if ($article_data['featured']) echo 'checked'; ?>>Featured
								</label>
								<label class="checkbox-inline">
									<input type="checkbox" id="published" name="published"
									       value="" <?php if (!$article_data['published']) echo 'checked'; ?>>Disabled
								</label>
							</div>
							<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
							<input type="hidden" name="article_id" id="article_id"
							       value="<?php echo $article_data['article_id']; ?>">
							<button type="submit" class="btn btn-primary pull-right" id="submit" name="submit"><i
									class="fa fa-save"></i>Save
							</button>
							<button type="reset" class="btn btn-default" id="reset" name="reset">Reset</button>

						</div>
						<!-- /.col-lg-6 (nested) -->
						<div class="col-lg-4">
							<input type='file' id="article_image" name="article_image"><br>
							<img class="" id="img_preview" src="<?php echo $article_data['article_image_link']; ?>"
							     height="400" width="320" alt="your image"/>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!--End Advanced Tables -->
	</div>
</div>
<!-- /. ROW  -->

<?php include('inc/footer.php'); ?>
<script src="../assets/admin/js/tinymce/tinymce.min.js"></script>
<script>
	$(document).ready(function () {
		tinymce.init({
			selector    : '#article_content',
			skin        : 'lightgray_gradient',
			height      : 300,
			theme       : 'modern',
			plugins     : [
				'advlist autolink lists link image charmap print preview hr anchor pagebreak',
				'searchreplace wordcount visualblocks visualchars code fullscreen',
				'insertdatetime media nonbreaking save table contextmenu directionality',
				'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
			],
			toolbar1    : 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
			toolbar2    : 'print preview media | forecolor backcolor emoticons | codesample',
			image_advtab: true
		});
	});

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#img_preview').attr('src', e.target.result);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}
	$("#article_image").change(function () {
		readURL(this);
	});

	$('#article_form').submit(function (event) {
		$('.form-group').removeClass('has-error');
		$('.help-block').remove();
		$('#message').empty();

		var formData = new FormData($(this)[0]);
		// process the form
		$.ajax({
				type       : 'POST',
				url        : 'lib/set_article.php',
				data       : formData,
				dataType   : 'json',
				cache      : false,
				contentType: false,
				processData: false
			})
			// using the done promise callback
			.done(function (data) {
				if (!data.success) {
					if (data.message) {
						$('#message').append('<div class="alert alert-danger" ><buttton class="close" data-dismiss="alert"><i class= "fa-fw fa fa-times"></i></buttton><i class = "fa-fw fa fa-times"></i>' + data.message + '</div>');
					}
					if (data.errors.article_id) {
						$('#message').append('<div class="help-block">' + data.errors.article_id + '</div>');
					}
					if (data.errors.article_title) {
						$('#article_title_group').addClass('has-error').append('<div class="help-block">' + data.errors.article_title + '</div>');
					}
					if (data.errors.category) {
						$('#category_group').addClass('has-error').append('<div class="help-block">' + data.errors.category + '</div>');
					}
					if (data.errors.article_content) {
						$('#article_content_group').addClass('has-error').append('<div class="help-block">' + data.errors.article_content + '</div>');
					}
					if (data.errors.article_image) {
						$('#article_image_group').addClass('has-error').append('<div class="help-block">' + data.errors.article_image + '</div>');
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
<!-- Custom Js -->
<script src="../assets/admin/js/custom-scripts.js"></script>
</body>
</html>
