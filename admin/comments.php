<?php
require_once('../include/init.php');
if (!$auth->isLoggedIn()) {
	header("location: login.php");
}
$article = array();
if (isset($_GET['id'])) {
	$article_obj = new article();
	$article_obj->setArticleId($_GET['id']);
	$article = $article_obj->getArticle();
}
?>

<?php include('inc/header.php'); ?>

<?php
$page = 'articles';
include('inc/nav.php');
?>

<!-- /. ROW  -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel"
     aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="confirm_form">
				<div class="modal-body">
					<p class="alert alert-danger" id="alert_fail">Are you sure you want to delete this Comment?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="submit" class="btn btn-primary" id="confirm_delete"><input id="confirm_delete_input"
					                                                                         type="hidden">Yes
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">

		<!-- Advanced Tables -->
		<div class="panel panel-default">
			<div class="panel-heading">
				Comments
				<span class="pull-right"><a href="articles.php"><?php echo $article['article_title']; ?></a></span>
			</div>
			<div id="message"></div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover display" id="comment_grid">
						<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Comment</th>
							<th>Time</th>
							<th>Blocked</th>
							<th>Actions</th>
						</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<!--End Advanced Tables -->
	</div>
</div>
<!-- /. ROW  -->

<?php include('inc/footer.php'); ?>
<script>
	$(document).ready(function () {
		$('#comment_grid').DataTable({
			"columns"   : [
				{"data": "name"},
				{"data": "email"},
				{"data": "comment"},
				{"data": "create_time"},
				{"data": "block"},
				{"data": "actions"}
			],
			"processing": true,
			"serverSide": true,
			"ajax"      : {
				url  : "lib/get_comments.php?article_id=<?php echo $_GET['id']; ?>", // json datasource
				type : "post",  // type of method  ,GET/POST/DELETE
				error: function () {
					$("#comment_grid_processing").css("display", "none");
				}
			}
		});
	});

	function blockComment(id) {
		var status = $(id).children('.block').val();
		if (status == 'No')
			status = 1;
		else if (status == 'Yes')
			status = 0;

		var formData = {
			'comment_id': $(id).children('.delete_input').val(),
			'mode'      : 'edit',
			'block'     : status
		};
		$.ajax({
			type    : 'POST',
			url     : 'lib/set_comment.php',
			data    : formData,
			dataType: 'json',
			encode  : true
		}).done(function (data) {
			if (!data.success) {
				$('#message').empty().fadeIn('slow').append("<div class='alert alert-success text-center'><button class='close' data-dismiss='alert'>&times;</button><span>An error occurred while updating</span>");
			}
			else {
				$('#deleteModal').modal('hide');
				$('#comment_grid').DataTable().ajax.reload(null, false);
			}
		});
	}

	function confirmDelete(comment_id) {
		var id = $(comment_id).children('.delete_input').val();
		$('#confirm_delete_input').val(id);
	}


	$('#confirm_form').submit(function (event) {
		var formData = {
			'id'  : $('#confirm_delete_input').val(),
			'mode': 'delete'
		};
		$.ajax({
			type    : 'POST',
			url     : 'lib/set_comment.php',
			data    : formData,
			dataType: 'json',
			encode  : true
		}).done(function (data) {
			if (!data.success) {
				$('#message').empty().fadeIn('slow').append("<div class='alert alert-success text-center'><button class='close' data-dismiss='alert'>&times;</button><span>An error occurred while deleting</span>");
			}
			else {
				$('#deleteModal').modal('hide');
				$('#message').empty().slideDown(1000).append("<div class='alert alert-success text-center'><button class='close' data-dismiss='alert'>&times;</button><span>Comment deleted successfully</span>").slideUp(2000);
				$('#comment_grid').DataTable().ajax.reload(null, false);
			}
		});

		event.preventDefault();
	});

</script>
<!-- Custom Js -->
<script src="../assets/admin/js/custom-scripts.js"></script>
</body>
</html>
