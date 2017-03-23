<?php
require_once('include/init.php');
$products_data = array();
$product = array();
$product_obj = new product();
$products_data = $product_obj->getAllProducts();

$categories = array();
$category_obj = new category();
$categories = $category_obj->getAllCategories();

$articles = array();
$article_obj = new article();
$articles = $article_obj->getAllArticles();
if (isset($_GET['id'])) {
	$product_obj->setProductId($_GET['id']);
	$product = $product_obj->getProduct();
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
				<li class=""><a href="products.php">Products</a></li>
				<li class="active"><?php echo $product['product_name']; ?></li>
			</ol>
		</div>
	</div>
	<!-- //breadcrumbs -->
	<!--- products --->
	<div class="products">
		<div class="container">
			<div class="agileinfo_single">

				<div class="col-md-4">
					<div class=" agileinfo_single_left">
						<img id="example"
						     src="<?php echo "images/products/" . $product['product_id'] . "/" . $product['product_image']; ?>"
						     alt=" " class="img-responsive">

					</div>
					<div class="clearfix"></div>
					<br>
					<section class="well">
						<p>Read more about this product</p><br>
						<?php
						foreach ($articles as $article) {
							if ($article['product_id'] != $product['product_id']) continue;
							?>
							<div class="row" style="font-size: 12px;">
								<div class="col-sm-3">
									<a href="articles.php?pid=<?php echo $article['article_id']; ?>"><img
											src="<?php echo "images/articles/" . $article['article_id'] . "/" . $article['article_image']; ?>"
											alt="<?php echo $article['article_image']; ?>" class="img-responsive"></a>
								</div>
								<div class="col-sm-9">
									<p>
										<a href="articles.php?pid=<?php echo $article['article_id']; ?>"><?php echo $article['article_title']; ?></a>
									</p>
								</div>
							</div><br>
							<?php
						}
						?>
					</section>
				</div>
				<div class="col-md-8 agileinfo_single_right">
					<h3><?php echo $product['product_name']; ?></h3><br>
					<h5><?php echo $product['product_short_description']; ?></h5>
					<div class="w3agile_description">
						<h4>Description :</h4>
						<p><?php echo $product['product_description']; ?></p>
					</div>
					<div class="snipcart-item block">
						<div class="snipcart-thumb agileinfo_single_right_snipcart">
							<h4 class="m-sing">&#8358;<?php echo number_format($product['product_price'], 2); ?></h4>
						</div>
						<!-- <div class="row">
							<div class="col-md-5">
								<span style="color: #fa4b2a;"><i class="fa fa-phone"></i><b> <?php //echo $user['phone']; ?></b></span>
							</div>
							<div class="col-md-5">
								<span style="color: #fa4b2a;"><i class="fa fa-envelope"></i><b> <?php //echo $user['email']; ?></b> </span>
							</div>
						</div>-->
						<div class="snipcart-details well">
							<form role="form" action="" method="post" id="order_form" name="order_form">
								<div id="msg"></div>
								<fieldset>
									<div class="form-group row">
										<div class="col-md-2">
											<label class="">Phone:</label>
										</div>
										<div class="col-md-10">
											<input name="phone" id="phone" type="text" placeholder="Enter phone number"
											       class="form-control">
										</div>


									</div>
									<div class="form-group row">
										<div class="col-md-2">
											<label class="">Email:</label>
										</div>
										<div class="col-md-10">
											<input name="email" id="email" type="email" placeholder="Enter your email"
											       class="form-control">
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-2">
											<label>Delivery Address:</label>
										</div>
										<div class="col-md-10">
											<textarea placeholder="Delivery Address" class="form-control" name="addrese"
											          id="address" rows="3"></textarea>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-2">
											<label>Quantity:</label>
										</div>
										<div class="col-md-10">
											<input name="quantity" id="quantity" type="number" placeholder="Quantity"
											       class="form-control" value="1">
										</div>

									</div>
								</fieldset>
								<input type="hidden" name="product_id" id="product_id"
								       value="<?php echo $product['product_id']; ?>">
								<input type="hidden" name="mode" id="mode" value="<?php echo 'new'; ?>">
								<input type="submit" id="submit" name="submit" value="Place Order" class="button">
							</form>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<!--- products --->
	<!-- //footer -->
	<?php include('include/layout/footer.php'); ?>
	<!-- //footer -->

	<!-- //scripts -->
	<?php include('include/layout/scripts.php'); ?>
	<!-- //scripts -->
	<script>
		$('#order_form').submit(function (event) {
			$.blockUI({
				message: '<h1 style=""><img style="" width="100" height="100" src="images/ajax_loader_blue.gif" />Processing Order...</h1>',
				css    : {backgroundColor: '#grey', opacity: 0.3, color: '#fff', border: '0px solid #C1DAD7'}
			});
			$('submit').prop('disabled', true);
			var formdata = {
				"product_id": $('#product_id').val(),
				"phone"     : $('#phone').val(),
				"email"     : $('#email').val(),
				"address"   : $('#address').val(),
				"quantity"  : $('#quantity').val(),
				"mode"      : $('#mode').val(),
				"status"    : 'PENDING'
			};

			if (formdata.product_id == '') {
				$.unblockUI();
				$('#msg').empty().append("<p class='alert alert-danger'>Product ID is not set</p>");

			}
			else if (formdata.phone == '' && formdata.email == '') {
				$.unblockUI();
				$('#msg').empty().append("<p class='alert alert-danger'>Please provide phone number or email</p>");
			}
			else if (formdata.quantity == '' || parseInt(formdata.quantity) < 1) {
				$.unblockUI();
				$('#msg').empty().append("<p class='alert alert-danger'>Please specify quantity</p>");
			}
			else {
				$.ajax({
						type    : 'POST',
						url     : 'admin/lib/set_order.php',
						data    : formdata,
						dataType: 'json',
						encode  : true
					})
					.done(function (data) {
						$.unblockUI();
						if (!data.success) {
							if (data.message) {
								$('#msg').empty().append("<p class='alert alert-danger'>" + data.message + "</p>");
							}
						}
						else {
							$('#msg').empty().append("<p class='alert alert-success'>We have received your order. We will contact you shortly</p>");
							$('submit').prop('disabled', false);
						}
					});
			}
			event.preventDefault();
		});

	</script>
</body>
</html>