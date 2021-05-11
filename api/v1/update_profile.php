<?php

/**
 * Script called on Profile Update API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');

$userId = htmlentities($_POST['userId'], ENT_COMPAT, 'UTF-8');
$firstName = htmlentities($_POST['firstName'], ENT_COMPAT, 'UTF-8');
$lastName = htmlentities($_POST['lastName'], ENT_COMPAT, 'UTF-8');
$email = htmlentities($_POST['email'], ENT_COMPAT, 'UTF-8');
$countryCode = htmlentities($_POST['countryCode'], ENT_COMPAT, 'UTF-8');
$phoneNo = htmlentities($_POST['phoneNo'], ENT_COMPAT, 'UTF-8');
$streetAddress = htmlentities($_POST['streetAddress'], ENT_COMPAT, 'UTF-8');
$pinCode = htmlentities($_POST['pinCode'], ENT_COMPAT, 'UTF-8');
$country = htmlentities($_POST['country'], ENT_COMPAT, 'UTF-8');
$state = htmlentities($_POST['state'], ENT_COMPAT, 'UTF-8');
$city = htmlentities($_POST['city'], ENT_COMPAT, 'UTF-8');
$company = htmlentities($_POST['company'], ENT_COMPAT, 'UTF-8');
if (isset($_POST['profileImage'])) {
	$profileImage = $_POST['profileImage'];
} else {
	$profileImage = '';
}

if (empty($userId)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'User Id is required'));
	displayOutput($response);
}

if (empty($firstName)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'First Name is required'));
	displayOutput($response);
}

if (empty($lastName)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Last Name is required'));
	displayOutput($response);
}

if (empty($email)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Email Id is required'));
	displayOutput($response);
}

if (empty($countryCode)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Country Code is required'));
	displayOutput($response);
}

if (empty($phoneNo)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Phone is required'));
	displayOutput($response);
}

if (empty($streetAddress)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Address is required'));
	displayOutput($response);
}

if (empty($pinCode)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Pincode is required'));
	displayOutput($response);
}

if (empty($country)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Country is required'));
	displayOutput($response);
}

if (empty($state)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'State is required'));
	displayOutput($response);
}

if (empty($city)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'City is required'));
	displayOutput($response);
}

if (empty($company)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Company is required'));
	displayOutput($response);
}

$allowedExts = array("jpg", "jpeg", "gif", "png", "JPG");
$extension = @end(explode(".", $_FILES["profileImage"]["name"]));

if (!empty($_FILES["profileImage"]["name"])) {

	if ((($_FILES["profileImage"]["type"] == "image/gif")
			|| ($_FILES["profileImage"]["type"] == "image/jpeg")
			|| ($_FILES["profileImage"]["type"] == "image/JPG")
			|| ($_FILES["profileImage"]["type"] == "image/png")
			|| ($_FILES["profileImage"]["type"] == "image/pjpeg"))
		&& ($_FILES["profileImage"]["size"] < 1048576)
		&& in_array($extension, $allowedExts)
	) {

		if ($_FILES["profileImage"]["error"] > 0) {

			$response = array('status' => array('error_code' => 1, 'message' => $_FILES["profileImage"]["error"]));
			displayOutput($response);
		} else {

			$pic = $_FILES["profileImage"]["name"];
			$conv = explode(".", $pic);
			$ext = $conv['1'];
			$file = $firstName . "-" . rand(10, 100) . "." . $ext;
			move_uploaded_file($_FILES["profileImage"]["tmp_name"], SYSBASE . "medias/users/" . $file);
			$url = $file;

			$result_exists = $db->query('UPDATE pm_user SET firstname = "' . $firstName . '", lastname = "' . $lastName . '", email = "' . $email . '", country_code = "' . $countryCode . '", mobile = "' . $phoneNo . '", address = "' . $streetAddress . '", postcode = "' . $pinCode . '", country = "' . $country . '", state = "' . $state . '", city = "' . $city . '", company = "' . $company . '", user_image = "' . $url . '" WHERE (id = ' . $userId . ')');

			if ($result_exists) {

				//Push Notification FOR Android
				$tokenSql = $db->query('SELECT * FROM pm_device WHERE user_id  = "' . $userId . '" AND device_type = 1');
				$rowToken = $tokenSql->fetch();

				$fcm_token = $rowToken['fcm_token'];
				$deviece_type = $rowToken['device_type'];
				$title = 'Profile Update';
				$body = 'Hi, Your Profile is Successfully Updated.';

				push_notify_send($fcm_token, $deviece_type, $title, $body);
				//Push Notification FOR Android

				//Notification List					
				$noti = array();
				$noti['id'] = null;
				$noti['user_id'] = $userId;
				$noti['action_id'] = $userId;
				$noti['notification_title'] = 'Profile Update';
				$noti['notification_desc'] = 'Hi, Your Profile is Successfully Updated.';
				$noti['action_url'] = null;
				$noti['status'] = '0';
				$noti['created_ts'] = date('Y-m-d h:i:s');

				$insertNoti = db_prepareInsert($db, 'pm_notification', $noti);
				$insertNoti->execute();
				//Notification List 			 

				$response = array('status' => array('error_code' => 0, 'message' => 'Profile Successfully Updated'));
				displayOutput($response);
			} else {
				$response = array('status' => array('error_code' => 1, 'message' => 'Something is Wrong. Try Again'));
				displayOutput($response);
			}
		}
	} else {

		$response = array('status' => array('error_code' => 1, 'message' => 'File Size Limit Crossed 1 MB Use Picture Size less than 1 MB'));
		displayOutput($response);
	}
} else {

	$result_exists = $db->query('UPDATE pm_user SET firstname = "' . $firstName . '", lastname = "' . $lastName . '", email = "' . $email . '", country_code = "' . $countryCode . '", mobile = "' . $phoneNo . '", address = "' . $streetAddress . '", postcode = "' . $pinCode . '", country = "' . $country . '", state = "' . $state . '", city = "' . $city . '", company = "' . $company . '" WHERE (id = ' . $userId . ')');

	if ($result_exists) {

		//Push Notification FOR Android
		$tokenSql = $db->query('SELECT * FROM pm_device WHERE user_id  = "' . $userId . '" AND device_type = 1');
		$rowToken = $tokenSql->fetch();

		$fcm_token = $rowToken['fcm_token'];
		$deviece_type = $rowToken['device_type'];
		$title = 'Profile Update';
		$body = 'Hi, Your Profile is Successfully Updated.';

		push_notify_send($fcm_token, $deviece_type, $title, $body);
		//Push Notification FOR Android

		//Notification List					
		$noti = array();
		$noti['id'] = null;
		$noti['user_id'] = $userId;
		$noti['action_id'] = $userId;
		$noti['notification_title'] = 'Profile Update';
		$noti['notification_desc'] = 'Hi, Your Profile is Successfully Updated.';
		$noti['action_url'] = null;
		$noti['status'] = '0';

		$insertNoti = db_prepareInsert($db, 'pm_notification', $noti);
		$insertNoti->execute();
		//Notification List 	 

		$response = array('status' => array('error_code' => 0, 'message' => 'Profile Successfully Updated'));
		displayOutput($response);
	} else {
		$response = array('status' => array('error_code' => 1, 'message' => 'Something is Wrong. Try Again'));
		displayOutput($response);
	}
}

displayOutput($response);
