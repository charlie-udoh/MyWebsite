<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 05/02/2017
 * Time: 09:21
 */
require_once('../../include/init.php');

$order_obj = new order();
$data = array();
if (isset($_POST['mode'])) {
	$mode = $_POST['mode'];
	if ($mode == 'edit' || $mode == 'new') {
		if ($mode == 'new') {
			$order_obj->setEmail($_POST['email']);
			$order_obj->setPhone($_POST['phone']);
			$order_obj->setAddress($_POST['address']);
			$order_obj->setQuantity($_POST['quantity']);
			$order_obj->setProductId($_POST['product_id']);
			$order_obj->setCreateTime(date('Y-m-d h:i:s'));
		}
		if ($mode == 'edit') {
			$order_obj->setOrderId($_POST['order_id']);
		}
		$order_obj->setStatus($_POST['status']);

		if (!$is_validated = $order_obj->validateOrder($mode)) {
			$error_messages = $order_obj->getValidateMessages();
			$data['success'] = false;
			$data['errors'] = $error_messages;
		} else {
			if ($mode == 'new') {
				$subject = 'NEW ORDER ON ELNET';
				$title = 'NEW ORDER ON ELNET.COM';
				$message = "<div>
							<h4>Created @ " . date('d/m/Y h:i a') . "</h4>
							<span><b>Product: </b> " . $order_obj->getProductName() . "</span><br>
							<span><b>Quantity: </b>" . $order_obj->getQuantity() . "</span><br>
							<span><b>Phone Number: </b>" . $order_obj->getPhone() . " </span><br>
							<span><b>Email: </b> " . $order_obj->getEmail() . "</span><br>
							<span><b>Delivery Address: </b>" . $order_obj->getAddress() . " </span><br>
							</div>";
				if (sendMail($subject, $title, $message)) {
					if (!$order_obj->insertOrder()) {
						$data['message'] = "New record was not created successfully";
						$data['success'] = false;
					} else {
						$data['message'] = "New record has been created successfully";
						$data['success'] = true;
					}
				} else {
					$data['message'] = "An error occurred while sending mail";
					$data['success'] = false;
				}
			} elseif ($mode == 'edit') {
				if (!$order_obj->updateOrder()) {
					$data['message'] = "Record was not updated successfully";
					$data['success'] = false;
				} else {
					$data['message'] = "Record has been updated successfully";
					$data['success'] = true;
				}
			}
		}
	} elseif ($mode == 'delete') {
		$order_obj->setOrderId($_POST['id']);
		if (!$order_obj->deleteOrder()) {
			$data['message'] = "Record was not deleted successfully";
			$data['success'] = false;
		} else {
			$data['message'] = "Record has been deleted successfully";
			$data['success'] = true;
		}
	} else {
		$data['message'] = "An error occurred. Mode is not set";
		$data['success'] = false;
	}

	echo json_encode($data);
}