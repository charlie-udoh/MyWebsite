<?php
require_once('../../include/config.php');
require_once('../../include/init.php');

/* IF Query comes from DataTables do the following */
if (!empty($_POST)) {
	define("MyTable", "orders");

	/* Useful $_POST Variables coming from the plugin */
	$draw = $_POST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	$orderByColumnIndex = $_POST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
	$orderBy = $_POST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
	$orderType = $_POST['order'][0]['dir']; // ASC or DESC
	$start = $_POST["start"];//Paging first record indicator.
	$length = $_POST['length'];//Number of records that the table can display in the current draw
	/* END of POST variables */

	$recordsTotal = $db->GetOne("SELECT count(order_id) FROM " . MyTable);

	/* SEARCH CASE : Filtered data */
	if (!empty($_POST['search']['value'])) {
		/* WHERE Clause for searching */
		for ($i = 0; $i < count($_POST['columns']); $i++) {
			if ($_POST['columns'][$i]['data'] == 'actions' || $_POST['columns'][$i]['data'] == 'product_name') continue;
			if ($_POST['columns'][$i]['data'] == 'create_time') $_POST['columns'][$i]['data'] = MyTable . '.create_time';
			$column = $_POST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			$where[] = "$column like '%" . $_POST['search']['value'] . "%'";
		}
		$where = "WHERE " . implode(" OR ", $where);// id like '%searchValue%' or name like '%searchValue%' ....
		/* End WHERE */

		$sql = sprintf("SELECT count(order_id) FROM %s %s", MyTable, $where);//Search query without limit clause (No pagination)
		$recordsFiltered = $db->GetOne($sql);//Count of search result

		/* SQL Query for search with limit and orderBy clauses*/
		$sql = "SELECT order_id, phone, email, address, quantity, status, product_name, DATE_FORMAT(" . MyTable . ".create_time, '%d/%m/%Y @ %h:%i %p') as create_time FROM " . MyTable . " INNER JOIN products ON products.product_id= orders.product_id $where ORDER BY $orderBy $orderType limit $start,$length ";
		$data = $db->GetAll($sql);
	} /* END SEARCH */
	else {
		$sql = "SELECT order_id, phone, email, address, quantity, status, product_name, DATE_FORMAT(" . MyTable . ".create_time, '%d/%m/%Y @ %h:%i %p') as create_time FROM " . MyTable . " INNER JOIN products ON products.product_id= orders.product_id ORDER BY $orderBy $orderType limit $start,$length ";
		$data = $db->GetAll($sql);
		$recordsFiltered = $recordsTotal;
	}

	for ($i = 0; $i < count($data); $i++) {
		$opt = '';
		if ($data[$i]['status'] == 'PENDING') {
			$opt .= "<button class='btn btn-xs btn-success' type='button' onclick='markOrder(this)' title='Mark as Supplied'><input type='hidden' id='order_id' class= 'delete_input' value='" . $data[$i]['order_id'] . "'> <input type='hidden' id='ostatus' class= 'status' value='" . $data[$i]['status'] . "'><i class='fa fa-check'></i></button>";
		} else {
			$opt .= "<button class='btn btn-xs btn-primary'  type='button' onclick='markOrder(this)' title='Mark as NOT Supplied'> <input type='hidden' id='order_id' class= 'delete_input' value='" . $data[$i]['order_id'] . "'> <input type='hidden' id='ostatus' class= 'status' value='" . $data[$i]['status'] . "'> <i class='fa fa-undo'></i></button>";
		}
		$data[$i]['actions'] = "$opt
		<button class='btn btn-xs btn-danger' type='button' title='Delete' data-toggle='modal' data-target='#deleteModal' data-original-title='Delete' onclick='confirmDelete(this)'><i class='fa fa-times'></i><input type='hidden' id='order_id' class= 'delete_input' value='" . $data[$i]['order_id'] . "'></button>";
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