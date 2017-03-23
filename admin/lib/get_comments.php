<?php
require_once('../../include/init.php');

if (!empty($_POST)) {
	define("MyTable", "comments");

	$id = $_GET['article_id'];
	$draw = $_POST["draw"];
	$orderByColumnIndex = $_POST['order'][0]['column'];
	$orderBy = $_POST['columns'][$orderByColumnIndex]['data'];
	$orderType = $_POST['order'][0]['dir'];
	$start = $_POST["start"];
	$length = $_POST['length'];

	$recordsTotal = $db->GetOne("SELECT count(comment_id) FROM " . MyTable . " WHERE article_id= $id");

	if (!empty($_POST['search']['value'])) {
		for ($i = 0; $i < count($_POST['columns']); $i++) {
			if ($_POST['columns'][$i]['data'] == 'actions') continue;
			$column = $_POST['columns'][$i]['data'];
			$where[] = "$column like '%" . $_POST['search']['value'] . "%'";
		}
		$where = "WHERE " . implode(" OR ", $where);
		$where .= " AND (article_id= $id)";
		$sql = sprintf("SELECT count(comment_id) FROM %s %s", MyTable, $where);
		$recordsFiltered = $db->GetOne($sql);

		$sql = "SELECT comment_id, name, email, comment, DATE_FORMAT(create_time, '%d/%m/%Y @ %h:%i %p') as create_time, IF(block = 1, 'Yes', 'No') as block FROM " . MyTable . " $where ORDER BY $orderBy $orderType limit $start , $length ";
		$data = $db->GetAll($sql);
	} else {
		$sql = "SELECT comment_id, name, email, comment, DATE_FORMAT(create_time, '%d/%m/%Y @ %h:%i %p') as create_time, IF(block = 1, 'Yes', 'No') as block FROM " . MyTable . " WHERE article_id= $id ORDER BY $orderBy $orderType limit $start , $length ";
		$data = $db->GetAll($sql);
		$recordsFiltered = $recordsTotal;
	}

	for ($i = 0; $i < count($data); $i++) {
		$opt = '';
		if ($data[$i]['block'] == 'No') {
			$opt .= "<button class='btn btn-xs btn-warning' type='button' onclick='blockComment(this)' title='Block Comment'><input type='hidden' id='comment_id' class= 'delete_input' value='" . $data[$i]['comment_id'] . "'> <input type='hidden' id='block' class= 'block' value='" . $data[$i]['block'] . "'><i class='fa fa-warning'></i></button>";
		} else {
			$opt .= "<button class='btn btn-xs btn-primary'  type='button' onclick='blockComment(this)' title='Unblock Comment'> <input type='hidden' id='comment_id' class= 'delete_input' value='" . $data[$i]['comment_id'] . "'> <input type='hidden' id='block' class= 'block' value='" . $data[$i]['block'] . "'> <i class='fa fa-undo'></i></button>";
		}
		$data[$i]['actions'] = "$opt
		<button class='btn btn-xs btn-danger' type='button'  title='Delete' data-toggle='modal' data-target='#deleteModal' data-original-title='Delete' onclick='confirmDelete(this)'><i class='fa fa-times'></i><input type='hidden' id='comment_id' class= 'delete_input' value='" . $data[$i]['comment_id'] . "'></button>";
	}

	/* Response to client before JSON encoding */
	$response = array(
		"draw"            => intval($draw),
		"recordsTotal"    => $recordsTotal,
		"recordsFiltered" => $recordsFiltered,
		"data"            => $data
	);

	echo json_encode($response);

} else {
	echo "NO POST Query from DataTable";
}
?>