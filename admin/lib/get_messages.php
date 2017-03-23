<?php
require_once('../../include/init.php');

if (!empty($_POST)) {
	define("MyTable", "messages");

	$draw = $_POST["draw"];
	$orderByColumnIndex = $_POST['order'][0]['column'];
	$orderBy = $_POST['columns'][$orderByColumnIndex]['data'];
	$orderType = $_POST['order'][0]['dir'];
	$start = $_POST["start"];
	$length = $_POST['length'];
	
	$recordsTotal = $db->GetOne("SELECT count(message_id) FROM " . MyTable);
	
	if (!empty($_POST['search']['value'])) {
		for ($i = 0; $i < count($_POST['columns']); $i++) {
			if ($_POST['columns'][$i]['data'] == 'actions') continue;
			$column = $_POST['columns'][$i]['data'];
			$where[] = "$column like '%" . $_POST['search']['value'] . "%'";
		}
		$where = "WHERE " . implode(" OR ", $where);
		$sql = sprintf("SELECT count(message_id) FROM %s %s", MyTable, $where);
		$recordsFiltered = $db->GetOne($sql);
		
		$sql = "SELECT message_id, sender, email, message, DATE_FORMAT(create_time, '%d/%m/%Y @ %h:%i %p') as create_time FROM " . MyTable . " $where ORDER BY $orderBy $orderType limit $start , $length ";
		$data = $db->GetAll($sql);
	} else {
		$sql = "SELECT message_id, sender, email, message, DATE_FORMAT(create_time, '%d/%m/%Y @ %h:%i %p') as create_time FROM " . MyTable . " ORDER BY $orderBy $orderType limit $start , $length ";
		$data = $db->GetAll($sql);
		$recordsFiltered = $recordsTotal;
	}
	
	for ($i = 0; $i < count($data); $i++) {
		//$data[$i]['actions']= "<button class='btn btn-xs btn-danger' type='button'  title='Delete' data-toggle='modal' data-target='#deleteModal' data-original-title='Delete' onclick='confirmDelete(this)'><i class='fa fa-times'></i><input type='hidden' id='message_id' class= 'delete_input' value='".$data[$i]['message_id']."'></button>";
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