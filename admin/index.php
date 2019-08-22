<?php
require_once('../include/init.php');
if (!$auth->isLoggedIn()) {
	header("location: login.php");
}
include('inc/header.php');
?>

<?php
$page = 'dashboard';
include('inc/nav.php');
?>

<div class="row">
	<div class="col-md-12">
		<h1 class="page-header">
			Dashboard
			<small>Summary of your Website</small>
		</h1>
	</div>
</div>
<!-- /. ROW  -->

<div class="row">
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-primary text-center no-boder bg-color-green">
			<div class="panel-body">
				<i class="fa fa-bar-chart-o fa-5x"></i>
				<h3>8,457</h3>
			</div>
			<div class="panel-footer back-footer-green">
				Daily Visits

			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-primary text-center no-boder bg-color-blue">
			<div class="panel-body">
				<i class="fa fa-shopping-cart fa-5x"></i>
				<h3>52,160 </h3>
			</div>
			<div class="panel-footer back-footer-blue">
				Sales

			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-primary text-center no-boder bg-color-red">
			<div class="panel-body">
				<i class="fa fa fa-comments fa-5x"></i>
				<h3>15,823 </h3>
			</div>
			<div class="panel-footer back-footer-red">
				Comments

			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-primary text-center no-boder bg-color-brown">
			<div class="panel-body">
				<i class="fa fa-users fa-5x"></i>
				<h3>36,752 </h3>
			</div>
			<div class="panel-footer back-footer-brown">
				No. of Visits

			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-9 col-sm-12 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Bar Chart Example
			</div>
			<div class="panel-body">
				<div id="morris-bar-chart"></div>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Donut Chart Example
			</div>
			<div class="panel-body">
				<div id="morris-donut-chart"></div>
			</div>
		</div>
	</div>

</div>
<!-- /. ROW  -->

<?php include('inc/footer.php'); ?>
<!-- Custom Js -->
<script src="../assets/admin/js/custom-scripts.js"></script>
<script>

</script>

</body>

</html>