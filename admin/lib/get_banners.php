<?php
require_once('../../include/config.php');
require_once('../../include/init.php');

/* IF Query comes from DataTables do the following */
if (!empty($_POST)) {
	define("MyTable", "banners");

	/* Useful $_POST Variables coming from the plugin */
	$draw = $_POST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	$orderByColumnIndex = $_POST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
	$orderBy = $_POST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
	$orderType = $_POST['order'][0]['dir']; // ASC or DESC
	$start = $_POST["start"];//Paging first record indicator.
	$length = $_POST['length'];//Number of records that the table can display in the current draw
	/* END of POST variables */

	$recordsTotal = $db->GetOne("SELECT count(banner_id) FROM " . MyTable);

	/* SEARCH CASE : Filtered data */
	if (!empty($_POST['search']['value'])) {
		/* WHERE Clause for searching */
		for ($i = 0; $i < count($_POST['columns']); $i++) {
			if ($_POST['columns'][$i]['data'] == 'actions') continue;
			$column = $_POST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			$where[] = "$column like '%" . $_POST['search']['value'] . "%'";
		}
		$where = "WHERE " . implode(" OR ", $where);// id like '%searchValue%' or name like '%searchValue%' ....
		/* End WHERE */

		$sql = sprintf("SELECT count(banner_id) FROM %s %s", MyTable, $where);//Search query without limit clause (No pagination)
		$recordsFiltered = $db->GetOne($sql);//Count of search result

		/* SQL Query for search with limit and orderBy clauses*/
		$sql = sprintf("SELECT banner_id, banner_description, IF(published = 1, 'Active', 'Inactive') AS published FROM %s %s ORDER BY %s %s LIMIT %d , %d ", MyTable, $where, $orderBy, $orderType, $start, $length);
		$data = $db->GetAll($sql);
	} /* END SEARCH */
	else {
		$sql = sprintf("SELECT banner_id, banner_image, banner_description, IF(published = 1, 'Active', 'Inactive') AS published FROM %s ORDER BY %s %s LIMIT %d , %d ", MyTable, $orderBy, $orderType, $start, $length);
		$data = $db->GetAll($sql);
		$recordsFiltered = $recordsTotal;
	}

	for ($i = 0; $i < count($data); $i++) {
		$data[$i]['actions'] = "<a href= 'banner_form.php?mode=edit&id=" . $data[$i]['banner_id'] . "' class='btn btn-xs btn-primary' title='Edit'><i class='fa fa-pencil'></i></a>
		<button class='btn btn-xs btn-danger' type='button'  title='Delete' data-toggle='modal' data-target='#deleteModal' data-original-title='Delete' onclick='confirmDelete(this)'><i class='fa fa-times'></i><input type='hidden' id='banner_id' class= 'delete_input' value='" . $data[$i]['banner_id'] . "'></button>";
		$data[$i]['banner_image'] = "<a href='../images/banners/" . $data[$i]['banner_id'] . "/" . $data[$i]['banner_image'] . "' target='_blank'>" . $data[$i]['banner_image'] . "</a>";
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