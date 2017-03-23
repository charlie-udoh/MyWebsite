<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 25/02/2017
 * Time: 18:22
 */

/**
 * this function handles saving of images to the designated folder
 * @param $folder
 * @param $file_obj
 * @param $id
 * @return bool
 */
function createNewFileName($file_name, $id) {
	$temp = explode('.', $file_name);
	$new_filename = $id . '.' . end($temp);

	return $new_filename;
}

function saveImage($folder, $file_obj, $id) {
	$path = ROOT . IMAGES_DIR . $folder . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
	if (!file_exists($path)) {
		mkdir($path, 0777, true);
	}
	if (!move_uploaded_file($file_obj["tmp_name"], $path . $file_obj["new_name"])) return false;

	return true;
}

function deleteImage($folder, $id) {
	$path = ROOT . IMAGES_DIR . $folder . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;

	if (file_exists($path)) {
		array_map('unlink', glob("$path*.*"));
		rmdir($path);
	}

	return true;
}

function sendMail($subject = '', $title = '', $message = '') {
	require('PHPMailer/class.phpmailer.php');
	require('PHPMailer/class.smtp.php');
	date_default_timezone_set(DEFAULT_TIMEZONE);
	global $user;

	if (empty(trim($subject))) {
		$subject = "NEW ORDER";
	}
	if (empty(trim($title))) {
		$subject = "NEW ORDER";
	}

	try {
		$mail = new PHPMailer(true);
		$mail->isSMTP();
		//$mail->SMTPDebug = 2; //comment out this code for ajax request to work
		$mail->Host = SMTP_HOST;
		$mail->Port = SMTP_PORT;
		$mail->SMTPAuth = true;
		$mail->Username = SMTP_USER;
		$mail->Password = SMTP_PASS;
		$mail->SMTPSecure = 'ssl';
		$mail->From = SMTP_USER;
		$mail->FromName = "Forever Living Products";
		$mail->addAddress($user['email'], 'Order Information'); //Name is optional
		//$mail->addReplyTo("bassey@dqdemos.com","Espread System");
		$mail->isHTML(true);
		$mail->Subject = "$subject";
		$mail->AltBody = 'Forever Living Products' . $message;
		$mail->Body = '<div style="width: 610px;">
<div style="width: 610px;">
        <div style="width: 600px; background-color: #fa4b2a;; color: #fff; padding: 10px; text-align: center; font-weight: bold;">
            <span style="">' . $title . '</span><hr/>
        </div>
        <div style="width: 610px; background-color: white; padding: 5px; color: black; font-weight: bold;">
            <span>' . $message . '</span>
        </div>
    </div>
</div>';

		if (!$mail->send()) return FALSE;

		return TRUE;
	} catch (phpmailerException $e) {
		return FALSE;
		//echo $e->errorMessage(); //error messages from PHPMailer
	} catch (Exception $e) {
		return FALSE;
		//echo $e->getMessage();
	}
}