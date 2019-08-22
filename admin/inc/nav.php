<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 03/02/2017
 * Time: 22:26
 */
?>

<body>
	<div id="wrapper">
		<nav class="navbar navbar-default top-navbar" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">myadmin</a>
			</div>

			<ul class="nav navbar-top-links navbar-right">
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
						<i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-user">
						<li><a href="userprofile.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
						</li>
						<!-- <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
						</li> -->
						<li class="divider"></li>
						<li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
						</li>
					</ul>
					<!-- /.dropdown-user -->
				</li>
				<!-- /.dropdown -->
			</ul>
		</nav>
		<!--/. NAV TOP  -->
		<nav class="navbar-default navbar-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav" id="main-menu">

					<li>
						<a class="<?php if ($page == 'dashboard') echo 'active-menu' ?>" href="index.php"><i
								class="fa fa-dashboard"></i> Dashboard</a>
					</li>
					<li>
						<a href="categories.php" class="<?php if ($page == 'categories') echo 'active-menu' ?>"><i
								class="fa fa-sitemap"></i> Categories</a>
					</li>
					<li>
						<a href="products.php" class="<?php if ($page == 'products') echo 'active-menu' ?>"><i
								class="fa fa-shopping-cart"></i> Products</a>
					</li>
					<li>
						<a href="orders.php" class="<?php if ($page == 'orders') echo 'active-menu' ?>"><i
								class="fa fa-download"></i> Orders</a>
					</li>
					<li>
						<a href="articles.php" class="<?php if ($page == 'articles') echo 'active-menu' ?>"><i
								class="fa fa-sitemap"></i> Blog</a>
					</li>
					<li>
						<a href="banners.php" class="<?php if ($page == 'banners') echo 'active-menu' ?>"><i
								class="fa fa-sitemap"></i> Banners</a>
					</li>
					<li>
						<a class="<?php if ($page == 'messages') echo 'active-menu' ?>" href="messages.php"><i
								class="fa fa-desktop "></i> Messages</a>
					</li>
				</ul>

			</div>

		</nav>
		<!-- /. NAV SIDE  -->
		<div id="page-wrapper">
			<div id="page-inner">