<?php
require_once('../include/init.php');
if (!$auth->isLoggedIn()) {
	header("location: login.php");
}
$mode = '';
$product_data = array();
$cat_data = array();

$category_obj = new category();
$product_obj = new product();

$product_data['product_id'] = $product_data['product_name'] = $product_data['product_image'] = $product_data['product_description'] = $product_data['product_short_description'] = $product_data['product_quantity'] = $product_data['product_price'] = $product_data['published'] = $product_data['featured'] = $product_data['product_description'] = $product_data['product_image_link'] = '';

if (isset($_GET['mode'])) {
	if ($_GET['mode'] == 'new') $mode = 'new';
	else $mode = 'edit';
	if ($mode == 'edit') {
		$product_obj->setProductId($_GET['id']);
		$product_data = $product_obj->getProduct();
		$product_data['product_image_link'] = "../images/products/" . $product_data['product_id'] . '/' . $product_data['product_image'];
	}
}
//build category options for select element
$categories = $category_obj->getAllCategories();
$options = "<option value=''>--Select a category--</option>";
foreach ($categories as $category) {
	$selected = '';
	if (isset($product_data['category_id']))
		if ($product_data['category_id'] == $category['category_id']) $selected = 'selected';
	$options .= "<option value='" . $category['category_id'] . "' $selected>" . $category['category_name'] . "</option>";
}
?>

<?php include('inc/header.php'); ?>

<?php
$page = 'products';
include('inc/nav.php');
?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<?php echo ucfirst($mode); ?> Product
				<a href="products.php" class="pull-right">
					Back to Product List
				</a>
			</div>
			<div class="panel-body">
				<div id="message"></div>
				<form role="form" method="post" id="product_form" name="product_form" action=""
				      enctype="multipart/form-data">
					<div class="row">
						<div class="col-lg-8">
							<div class="form-group" id="pro_name_group">
								<label>Name</label>
								<input class="form-control" id="product_name" name="product_name"
								       placeholder="Enter name" value="<?php echo $product_data['product_name']; ?>">
							</div>
							<div class="form-group" id="pro_short_description_group">
								<label>Short Description</label>
								<input class="form-control" id="product_short_description"
								       name="product_short_description" placeholder="Enter short description"
								       value="<?php echo $product_data['product_short_description']; ?>">
							</div>
							<div class="form-group" id="pro_description_group">
								<label>Description</label>
								<textarea id="product_description" name="product_description" class="form-control"
								          rows="3"><?php echo $product_data['product_description']; ?></textarea>
							</div>
							<div class="form-group" id="pro_price_group">
								<label>Price</label>
								<div class="input-group">
									<span class="input-group-addon">&#8358;</span>
									<input class="form-control" id="product_price" name="product_price"
									       placeholder="Enter price"
									       value="<?php echo $product_data['product_price']; ?>">
									<span class="input-group-addon">.00</span>
								</div>
							</div>
							<div class="form-group" id="pro_quantity_group">
								<label>Quantity</label>
								<input class="form-control" id="product_quantity" name="product_quantity"
								       placeholder="Enter quantity"
								       value="<?php echo $product_data['product_quantity']; ?>">
							</div>
							<div class="form-group" id="pro_category_group">
								<label>Category</label>
								<select class="form-control" id="category" name="category">
									<?php echo $options; ?>
								</select>
							</div>
							<div class="form-group" id="published_group">
								<label class="checkbox-inline">
									<input type="checkbox" id="published" name="published"
									       value="" <?php if (!$product_data['published']) echo 'checked'; ?>>Disabled
								</label>
								<label class="checkbox-inline pull-right">
									<input type="checkbox" id="featured" name="featured"
									       value="" <?php if ($product_data['featured']) echo 'checked'; ?>>Featured
									Product
								</label>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group" id="pro_image_group">
								<label>Upload pictures for product</label>
								<input type="file" name="product_image" id="product_image"><br>
							</div>
							<img class="" id="img_preview" src="<?php echo $product_data['product_image_link']; ?>"
							     height="400" width="320" alt="your image"/>

						</div>
					</div>

					<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
					<input type="hidden" name="product_id" id="product_id"
					       value="<?php echo $product_data['product_id']; ?>">
					<button type="submit" class="btn btn-primary pull-right" id="submit" name="submit"><i
							class="fa fa-save"></i>Save
					</button>
					<button type="reset" class="btn btn-default" id="reset" name="reset">Reset</button>

				</form>
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
	$('#product_form').submit(function (event) {
		$('.form-group').removeClass('has-error');
		$('.help-block').remove();
		$('#message').empty();
		
		var published = "off";
		var featured = "off";
		if (document.getElementById("published").checked) {
			published = "on";
		}
		if (document.getElementById("featured").checked) {
			featured = "on";
		}
		var formData = new FormData($(this)[0]);
		// process the form
		$.ajax({
				type       : 'POST',
				url        : 'lib/set_product.php',
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
					if (data.errors.product_id) {
						$('#message').append('<div class="help-block">' + data.errors.product_id + '</div>');
					}
					if (data.errors.product_name) {
						$('#pro_name_group').addClass('has-error').append('<div class="help-block">' + data.errors.product_name + '</div>');
					}
					if (data.errors.product_description) {
						$('#pro_description_group').addClass('has-error').append('<div class="help-block">' + data.errors.product_description + '</div>');
					}
					if (data.errors.product_price) {
						$('#pro_price_group').addClass('has-error').append('<div class="help-block">' + data.errors.product_price + '</div>');
					}
					if (data.errors.product_quantity) {
						$('#pro_quantity_group').addClass('has-error').append('<div class="help-block">' + data.errors.product_quantity + '</div>');
					}
					if (data.errors.product_image) {
						$('#pro_image_group').addClass('has-error').append('<div class="help-block">' + data.errors.product_image + '</div>');
					}
					if (data.errors.category_id) {
						$('#pro_category_group').addClass('has-error').append('<div class="help-block">' + data.errors.category_id + '</div>');
					}
				}
				else {
					$('#message').append('<div class="alert alert-success" ><buttton class="close" data-dismiss="alert"><i class= "fa-fw fa fa-times"></i></buttton><i class = "fa-fw fa fa-times"></i>' + data.message + '</div>');
				}
			});
		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});

	//if (window.File && window.FileList && window.FileReader) {
	//	$("#product_picture").on("change", function(e) {
	//		var files = e.target.files,
	//			filesLength = files.length;
	//		for (var i = 0; i < filesLength; i++) {
	//			var f = files[i];
	//			var fileReader = new FileReader();
	//			fileReader.onload = (function(e) {
	//				var file = e.target;
	//				$('#image_preview').append("<span class='img_thumb'><img src='"+e.target.result+"' height='100' width='100' style='border: 2px solid;' title= '"+file.name+"'><span class='remove' style='cursor: pointer;'>Remove image</span></span><br><br>");
	//
	//				$(".remove").click(function(){
	//					$(this).parent(".img_thumb").remove();
	//				});
	//			});
	//			fileReader.readAsDataURL(f);
	//		}
	//	});
	//} else {
	//	alert("Your browser doesn't support to File API")
	//}

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#img_preview').attr('src', e.target.result);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}
	$("#product_image").change(function () {
		readURL(this);
	});
</script>

</body>
</html>
