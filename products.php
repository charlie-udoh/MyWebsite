<?php
require_once('include/init.php');
$filter = '';
$products_data = array();
$products_obj = new product();
if (isset($_GET['category'])) {
	$filter = 'category';
	$products_obj->setCategoryId($_GET['category']);
}
if (isset($_GET['search'])) {
	$filter = 'search';
	$products_obj->setSearchString(addslashes($_GET['search']));
}
$products_data = $products_obj->getAllProducts($filter);

$categories = array();
$category_obj = new category();
$categories = $category_obj->getAllCategories();
$c_options = "<option value=''>--Select a category--</option>";
foreach ($categories as $category) {
	$selected = '';
	if (isset($_GET['category']) && $_GET['category'] == $category['category_id']) $selected = 'selected';
	$c_options .= "<option value='" . $category['category_id'] . "' $selected>" . $category['category_name'] . "</option>";
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
				<li class="active">Products</li>
			</ol>
		</div>
	</div>
	<!-- //breadcrumbs -->
	<!--- products --->
	<div class="products container">
		<div class="">
			<div class=" row">
				<div class="col-md-6"></div>
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-6">
							<label class="pull-right">Filter by category</label>
						</div>
						<div class="col-md-6">
							<select id="country1"
							        onchange="location.href='<?php basename($_SERVER['PHP_SELF']); ?>?category='+this.options[this.selectedIndex].value"
							        class="frm-field required sect">
								<?php echo $c_options; ?>
							</select>
						</div>

					</div>

				</div>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-9 products-right">

				<div class="agile_top_brands_grids">
					<?php
					if (!empty($products_data)) {
						foreach ($products_data as $product) {
							?>
							<div class="tc-ch wow fadeInDown" data-wow-duration=".8s" data-wow-delay=".2s">
								<div class="tch-img col-md-3">
									<a href="product_order.php?id=<?php echo $product['product_id']; ?>"><img
											src="<?php echo "images/products/" . $product['product_id'] . "/" . $product['product_image']; ?>"
											alt="<?php echo $product['product_image']; ?>" class="img-responsive"></a>

								</div>

								<div class="col-md-9">
									<h4>
										<a href="product_order.php?id=<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></a>
									</h4>
									<h6><?php echo $product['product_short_description']; ?></h6>
									<p><?php echo $product['product_description']; ?></p>
									<div class="bht1">
										<span class="price">
											Price: &#8358;<?php echo number_format($product['product_price'], 2); ?>
										</span>
										<a href="product_order.php?id=<?php echo $product['product_id']; ?>"
										   class="pull-right">Order Now</a>
									</div>
								</div>


								<div class="clearfix"></div>
							</div>
							<?php
						}
					} else {
						?>
						<div class="tc-ch wow fadeInDown" data-wow-duration=".8s" data-wow-delay=".2s">
							<div class="col-md-12">
								<h4 class="alert alert-info">
									<?php
									echo isset($_GET['category']) ?
										'Oops! there are no products under this category. ' : 'Sorry, we couldnt find what you are looking for. ' ?>
									<a href="products.php">Continue to view all Products</a></h4>
							</div>
							<div class="clearfix"></div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<!--- products --->
	<!-- //footer -->
	<?php include('include/layout/footer.php'); ?>
	<!-- //footer -->

	<!-- //scripts -->
	<?php include('include/layout/scripts.php'); ?>
	<!-- //scripts -->
</body>
</html>