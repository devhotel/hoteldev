<?php

/**
 * Script called on Change Password API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');


$data = json_decode(file_get_contents('php://input'), true);

$userId = $data['userId'];
$oldPassword = $data['oldPassword'];
$newPassword = $data['newPassword'];
$cnfPassword = $data['cnfPassword'];

if (empty($userId)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'User Id is required'));
	displayOutput($response);
}

if (empty($oldPassword)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Old Password is required'));
	displayOutput($response);
}

if (empty($newPassword)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'New Password is required'));
	displayOutput($response);
}

if (empty($cnfPassword)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Confirm Password is required'));
	displayOutput($response);
}

$result_exists = $db->query('SELECT pass, email FROM pm_user WHERE pass = "' . md5($oldPassword) . '" AND id = "' . $userId . '"');

if ($result_exists !== false && $db->last_row_count() > 0) {

	$rowExist = $result_exists->fetch();

	$updateQuery = $db->query('UPDATE pm_user SET pass = "' . md5($newPassword) . '" WHERE id = "' . $userId . '"');

	if ($updateQuery) {

		$mailContent = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;">
							<p style="margin: 20px 0 10px;">Your account New Password: ' . $newPassword . '</p>
						</td></tr>';
		$email = $rowExist['email'];
		$from_email = 'welcome@guptahotels.com';
		$from_name = 'Gupta Hotels';

		if (sendMail($email, $name, 'Account New Password', $mailContent, '', '', $from_email, $from_name) !== false) {

			//Push Notification FOR Android
			$tokenSql = $db->query('SELECT * FROM pm_device WHERE user_id  = "' . $userId . '" AND device_type = 1');
			$rowToken = $tokenSql->fetch();

			$fcm_token = $rowToken['fcm_token'];
			$deviece_type = $rowToken['device_type'];
			$title = 'Password Change';
			$body = 'Hi, Your Profile Password is Successfully Changed.';

			push_notify_send($fcm_token, $deviece_type, $title, $body);
			//Push Notification FOR Android

			//Notification List					
			$noti = array();
			$noti['id'] = null;
			$noti['user_id'] = $userId;
			$noti['action_id'] = $userId;
			$noti['notification_title'] = 'Password Change';
			$noti['notification_desc'] = 'Hi, Your Profile Password is Successfully Changed.';
			$noti['action_url'] = null;
			$noti['status'] = '0';
			$noti['created_ts'] = date('Y-m-d h:i:s');

			$insertNoti = db_prepareInsert($db, 'pm_notification', $noti);
			$insertNoti->execute();
			//Notification List


			$response = array('status' => array('error_code' => 0, 'message' => 'Password Successfully Changed & Mail Sent!'));
			displayOutput($response);
		} else {
			$response = array('status' => array('error_code' => 0, 'message' => 'Password Successfully Changed!'));
			displayOutput($response);
		}
	} else {
		$response = array('status' => array('error_code' => 1, 'message' => 'Something is Wrong. Please Try again!'));
		displayOutput($response);
	}
} else {
	$response = array('status' => array('error_code' => 1, 'message' => 'Password in Not Matched!'));
	displayOutput($response);
}
