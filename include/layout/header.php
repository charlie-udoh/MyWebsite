<div class="agileits_header container">
	<div class="w3l_offers">
		<!--<p>SALE UP TO 70% OFF. USE CODE "SALE70%" . <a href="products.php">SHOP NOW</a></p>-->
	</div>
	<div class="agile-login pull-right">
		<!--<ul>-->
		<!--	<li><a href="admin/login.php">Login</a></li>-->
		<!--</ul>-->
	</div>
	<div class="clearfix"></div>
</div>

<div class="logo_products container">
	<div class="">
		<!--<div class="w3ls_logo_products_left1">-->
		<!--	<ul class="phone_email">-->
		<!--		<li><i class="fa fa-phone" aria-hidden="true"></i>Order online or call us : (+0123) 234 567</li>-->
		<!---->
		<!--	</ul>-->
		<!--</div>-->
		<div class="w3ls_logo_products_left">
			<h1><a href="index.php">elnet</a></h1>
		</div>
		<div class="w3l_search">
			<input type="search" name="Search" id="search"
			       value="<?php echo isset($_GET['search']) ? str_replace('-', ' ', $_GET['search']) : '' ?>"
			       placeholder="Search for a Product..." required="">
			<button type="button" id="search_btn" class="btn btn-default search" aria-label="Left Align"
			        onclick="searchSite('product')">
				<i class="fa fa-search" aria-hidden="true"> </i>
			</button>
			<div class="clearfix"></div>
		</div>

		<div class="clearfix"></div>
	</div>
</div>