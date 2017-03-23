<?php
require_once('../../include/init.php');

/* IF Query comes from DataTables do the following */
if (!empty($_POST)) {
	define("MyTable", "products");

	/* Useful $_POST Variables coming from the plugin */
	$draw = $_POST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	$orderByColumnIndex = $_POST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
	$orderBy = $_POST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
	$orderType = $_POST['order'][0]['dir']; // ASC or DESC
	$start = $_POST["start"];//Paging first record indicator.
	$length = $_POST['length'];//Number of records that the table can display in the current draw
	/* END of POST variables */

	$recordsTotal = $db->GetOne("SELECT count(product_id) FROM " . MyTable);

	/* SEARCH CASE : Filtered data */
	if (!empty($_POST['search']['value'])) {
		/* WHERE Clause for searching */
		for ($i = 0; $i < count($_POST['columns']); $i++) {
			if ($_POST['columns'][$i]['data'] == 'actions' || $_POST['columns'][$i]['data'] == 'category_name') continue;
			$column = $_POST['columns'][$i]['data'];//we get the name of each column using its index from POST request
			if ($_POST['columns'][$i]['data'] == 'published')
				$where[] = "products.$column like '%" . $_POST['search']['value'] . "%'";
			else
				$where[] = "$column like '%" . $_POST['search']['value'] . "%'";
		}
		$where = "WHERE " . implode(" OR ", $where);// id like '%searchValue%' or name like '%searchValue%' ....
		/* End WHERE */

		$sql = sprintf("SELECT count(product_id) FROM %s %s", MyTable, $where);//Search query without limit clause (No pagination)
		$recordsFiltered = $db->GetOne($sql);//Count of search result

		/* SQL Query for search with limit and orderBy clauses*/
		$sql = sprintf("SELECT product_id, product_name, product_short_description, product_description, product_price, product_quantity, category_name, IF(" . MyTable . ".published = 1, 'Active', 'Inactive') as published, IF(featured = 1, 'Yes', 'No') as featured FROM %s INNER JOIN categories ON categories.category_id= " . MyTable . ".category_id %s ORDER BY %s %s limit %d , %d ", MyTable, $where, $orderBy, $orderType, $start, $length);
		$data = $db->GetAll($sql);
	} /* END SEARCH */
	else {
		$sql = sprintf("SELECT product_id, product_name, product_short_description, product_description, product_price, product_quantity, category_name, IF(" . MyTable . ".published = 1, 'Active', 'Inactive') as published, IF(featured = 1, 'Yes', 'No') as featured FROM %s INNER JOIN categories ON categories.category_id= " . MyTable . ".category_id ORDER BY %s %s limit %d , %d ", MyTable, $orderBy, $orderType, $start, $length);
		$data = $db->GetAll($sql);
		$recordsFiltered = $recordsTotal;
	}

	for ($i = 0; $i < count($data); $i++) {
		$data[$i]['product_price'] = number_format($data[$i]['product_price']);
		$data[$i]['product_quantity'] = number_format($data[$i]['product_quantity']);
		$data[$i]['actions'] = "<a href= 'product_form.php?mode=edit&id=" . $data[$i]['product_id'] . "' class='btn btn-xs btn-primary' title='Edit'><i class='fa fa-pencil'></i></a>
		<button class='btn btn-xs btn-danger' type='button'  title='Delete' data-toggle='modal' data-target='#deleteModal' data-original-title='Delete' onclick='confirmDelete(this)'><i class='fa fa-times'></i><input type='hidden' id='cat_id' class= 'delete_input' value='" . $data[$i]['product_id'] . "'></button>";
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