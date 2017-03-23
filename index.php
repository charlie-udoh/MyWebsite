<?php
require_once('include/init.php');
$banner_data = array();
$banner_obj = new banner();
$banner_data = $banner_obj->getAllBanners();

$products_data = array();
$products_obj = new product();
$products_data = $products_obj->getAllProducts('featured');

$articles = array();
$articles_obj = new article();
$articles = $articles_obj->getAllArticles('featured');
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

	<!-- main-slider -->
	<div class="container" style="padding-left: 0; padding-right: 0">
		<ul id="demo1">
			<?php
			if (!empty ($banner_data)) {
				foreach ($banner_data as $banner) { ?>
					<li>
						<img
							src="<?php echo "images/banners/" . $banner['banner_id'] . "/" . $banner['banner_image']; ?>"
							alt="<?php echo $banner['banner_image']; ?>"/>
						<!--Slider Description example-->
						<div class="slide-desc">
							<h3><?php echo $banner['banner_description']; ?></h3>
						</div>
					</li>
					<?php
				}
			} else {
				?>
				<li>
					<img src="images/11.jpg" alt=""/>
					<!--Slider Description example-->
					<div class="slide-desc">
						<h3>Buy Rice Products Are Now On Line With Us</h3>
					</div>
				</li>
				<?php
			}
			?>
		</ul>
	</div>

	<!-- //main-slider -->

	<!-- //top-header and slider -->

	<!-- new -->
	<div class="newproducts-w3agile container">
		<div class="">
			<div class="col-md-8 feat">
				<h3>Top products</h3>
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
										<span class="">
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
					}
					?>
					<!--<div class="tc-ch wow fadeInDown"  data-wow-duration=".8s" data-wow-delay=".2s">
					   <div class="tch-img col-md-3">
						   <a href="singlepage.html"><img src="images/t4.jpg" class="img-responsive" alt=""></a>
					   </div>

					   <div class="col-md-9">
						   <h4><a href="singlepage.html">This is the article title</a></h4>
						   <h6>BY <a href="singlepage.html">ADAM ROSE </a>JULY 10 2016.</h6>
						   <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud eiusmod tempor incididunt ut labore et dolore magna aliqua exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
						   <p>Ut enim ad minim veniam, quis nostrud eiusmod tempor incididunt ut labore et dolore magna aliqua exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
					   </div>

					   <div class="bht1">
						   <a href="singlepage.html">Continue Reading</a>
					   </div>
					   <div class="soci">
						   <ul>
							   <li class="hvr-rectangle-out"><a class="fb" href="#"></a></li>
							   <li class="hvr-rectangle-out"><a class="twit" href="#"></a></li>
							   <li class="hvr-rectangle-out"><a class="goog" href="#"></a></li>
							   <li class="hvr-rectangle-out"><a class="pin" href="#"></a></li>
							   <li class="hvr-rectangle-out"><a class="drib" href="#"></a></li>
						   </ul>
					   </div>
					   <div class="clearfix"></div>
				   </div> -->
					<div class="clearfix"></div>
				</div>

			</div>

			<aside class="col-md-4">
				<!-- //Right section -->
				<?php include('include/layout/aside.php'); ?>
			</aside>
		</div>
	</div>
	<!-- //new -->

	<!-- //footer -->
	<?php include('include/layout/footer.php'); ?>
	<!-- //footer -->

	<!-- //scripts -->
	<?php include('include/layout/scripts.php'); ?>
	<!-- //scripts -->
</body>
</html>