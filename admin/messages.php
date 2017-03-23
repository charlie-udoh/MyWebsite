<?php
require_once('../include/init.php');
if (!$auth->isLoggedIn()) {
	header("location: login.php");
}
?>

<?php include('inc/header.php'); ?>

<?php
$page = 'messages';
include('inc/nav.php');
?>

<!-- /. ROW  -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel"
     aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="confirm_form">
				<div class="modal-body">
					<p class="alert alert-danger" id="alert_fail">Are you sure you want to delete this Message?</p>
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
		<!--<a class="btn btn-primary pull-right" style="margin: 5px 10px 2px 10px;" href="banner_form.php?mode=new">Add New</a>-->
		<!-- Advanced Tables -->
		<div class="panel panel-primary">
			<div class="panel-heading">
				Messages
			</div>
			<div id="message"></div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover display" id="message_grid">
						<thead>
						<tr>
							<th>Time</th>
							<th>Name</th>
							<th>Email</th>
							<th>Message</th>
							<!--<th>Action</th>-->
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
		$('#message_grid').DataTable({
			"columns"   : [
				{"data": "create_time"},
				{"data": "sender"},
				{"data": "email"},
				{"data": "message"},
				//{"data": "actions"}
			],
			"processing": true,
			"serverSide": true,
			"ajax"      : {
				url  : "lib/get_messages.php", // json datasource
				type : "post",  // type of method  ,GET/POST/DELETE
				error: function () {
					$("#message_grid_processing").css("display", "none");
				}
			}
		});
	});

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
			url     : 'lib/set_message.php',
			data    : formData,
			dataType: 'json',
			encode  : true
		}).done(function (data) {
			if (!data.success) {
				$('#message').empty().fadeIn('slow').append("<div class='alert alert-success text-center'><button class='close' data-dismiss='alert'>&times;</button><span>An error occurred while deleting</span>");
			}
			else {
				$('#deleteModal').modal('hide');
				$('#message').empty().slideDown(1000).append("<div class='alert alert-success text-center'><button class='close' data-dismiss='alert'>&times;</button><span>Banner deleted successfully</span>").slideUp(2000);
				$('#banner_grid').DataTable().ajax.reload(null, false);
			}
		});

		event.preventDefault();
	});
</script>
<!-- Custom Js -->
<script src="../assets/admin/js/custom-scripts.js"></script>
</body>
</html>
