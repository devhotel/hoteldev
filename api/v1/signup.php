<?php

/**
 * Script called on Signup API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');

//$response = array("status" => array(), "response" => array());
//$data=json_decode(file_get_contents('php://input'), true);


//$email = $data['email'];
//$mobile = $data['mobile'];
//$password = $data['password'];



$email = htmlentities($_POST['email'], ENT_COMPAT, 'UTF-8');
$countryCode = htmlentities($_POST['countryCode'], ENT_COMPAT, 'UTF-8');
$mobile = htmlentities($_POST['mobile'], ENT_COMPAT, 'UTF-8');
$password = htmlentities($_POST['password'], ENT_COMPAT, 'UTF-8');
$fcm_token = $_POST['fcm_token'];
$device_type = $_POST['device_type'];


if (empty($email)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Email Id is required'));
	displayOutput($response);
}

if (empty($countryCode)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Country Code is required'));
	displayOutput($response);
}

if (empty($mobile)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Mobile is required'));
	displayOutput($response);
}

if (empty($password)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Password is required'));
	displayOutput($response);
}

if ($countryCode == '' || $countryCode == NULL) {
	$countryCode = 91;
}

$result_exists = $db->query('SELECT * FROM pm_user WHERE  (email = ' . $db->quote($email) . ' OR mobile = ' . $db->quote($mobile) . ')');

if ($result_exists !== false && $db->last_row_count() > 0) {
	$row = $result_exists->fetch();
	if ($email == $row['email']) {
		$response = array('status' => array('error_code' => 1, 'message' => 'Email Already Registered!'));
		displayOutput($response);
	}
	if ($mobile == $row['mobile']) {
		$response = array('status' => array('error_code' => 1, 'message' => 'Mobile Already Registered!'));
		displayOutput($response);
	}
} else {
	$nuid = 0;
	$data = array();
	$data['id'] = null;
	$data['users'] = 1;
	$data['firstname'] = 'Traveler';
	$data['lastname'] = 'Traveler';
	$data['login'] = $mobile;
	$data['pass'] = md5($password);
	$data['add_date'] = time();
	$data['checked'] = 1;
	$data['type'] = 'registered';
	$data['email'] = $email;
	$data['country_code'] = $countryCode;
	$data['mobile'] = $mobile;
	$data['otp'] = '';

	$result_user = db_prepareInsert($db, 'pm_user', $data);

	if ($result_user->execute() !== false) {

		$nuid = $db->lastInsertId();

		$result_userdetails = $db->query('SELECT id, email, firstname, lastname, address, postcode, city, country, company, country_code as countryCode, mobile FROM pm_user WHERE (id = ' . $nuid . ')');
		if ($result_exists !== false && $db->last_row_count() > 0) {
			$userrow = $result_userdetails->fetch();

			$_SESSION['user']['id'] = $nuid;
			$_SESSION['user']['login'] = 'Traveler';
			$_SESSION['user']['email'] = $email;
			$_SESSION['user']['name'] = 'Traveler';
			$_SESSION['user']['type'] = 'registered';

			$mailContent = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;">
					<p style="margin: 20px 0 10px;">Your account has been created with email address ' . $email . ' </p>
					<p style="margin: 20px 0 10px;">Your account Password ' . $password . ' </p>
					<p style="margin: 0 0 10px;">We hope to host you in the future.</p>
					<p style="margin: 0 0 10px;">Kindly <a href="' . getUrl(true) . DOCBASE . 'login" style="color: #00767b;text-align: center;font-weight: bold;display: inline-block;">log-in</a> to continue</p>
				 </td></tr>';
			$email = $email;
			$from_email = 'welcome@guptahotels.com';
			$from_name = 'Gupta Hotels';

			//sendMail($email, $name, 'Welcome to Gupta Hotels', $mailContent,'','',$from_email,$from_name);

			if (sendMail($email, 'Traveller', 'Welcome to Gupta Hotels', $mailContent, '', '', $from_email, $from_name) !== false) {

				//Add Device Token
				$device = array();
				$device['id'] = null;
				$device['user_id'] = $nuid;
				$device['device_type'] = $device_type;
				$device['fcm_token'] = $fcm_token;

				$insertDevice = db_prepareInsert($db, 'pm_device', $device);
				$insertDevice->execute();
				//Add Device Token


				//Push Notification FOR Android
				$tokenSql = $db->query('SELECT * FROM pm_device WHERE user_id  = "' . $nuid . '" AND device_type = 1');
				$rowToken = $tokenSql->fetch();

				$fcm_token = $rowToken['fcm_token'];
				$deviece_type = $rowToken['device_type'];
				$title = 'Registration Successful';
				$body = 'Welcome to Gupta Hotels. Your registration is successful.';

				push_notify_send($fcm_token, $deviece_type, $title, $body);
				//Push Notification FOR Android


				//Notification List					
				$noti = array();
				$noti['id'] = null;
				$noti['user_id'] = $nuid;
				$noti['action_id'] = $nuid;
				$noti['notification_title'] = 'Registration Successful';
				$noti['notification_desc'] = 'Welcome to Gupta Hotels. Your registration is successful.';
				$noti['action_url'] = null;
				$noti['status'] = '0';
				$noti['created_ts'] = date('Y-m-d h:i:s');

				$insertNoti = db_prepareInsert($db, 'pm_notification', $noti);
				$insertNoti->execute();
				//Notification List


				$response = array('status' => array('error_code' => 0, 'message' => 'Successfully Registered'), 'response' => array('user' => $userrow));
				displayOutput($response);
			} else {
				$response = array('status' => array('error_code' => 1, 'message' => 'ACCOUNT_CREATE_FAILURE!'));
				displayOutput($response);
			}
		} else {
			$response = array('status' => array('error_code' => 1, 'message' => 'ACCOUNT_CREATE_FAILURE!'));
			displayOutput($response);
		}
	} else {
		$response = array('status' => array('error_code' => 1, 'message' => 'ACCOUNT_CREATE_FAILURE!'));
		displayOutput($response);
	}
}

displayOutput($response);
