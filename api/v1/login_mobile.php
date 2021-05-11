<?php

/**
 * Script called on Login With Mobile API
 */
require_once('../../common/lib.php');
require_once('../../common/define.php');
$phone = htmlentities($_POST['phoneNo'], ENT_COMPAT, 'UTF-8');
$countrycode = htmlentities($_POST['countryCode'], ENT_COMPAT, 'UTF-8');
$fcm_token = $_POST['fcm_token'];
$device_type = $_POST['device_type'];
if (empty($phone)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Phone Number is required'));
	displayOutput($response);
}
if (empty($countrycode)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Country Code is required'));
	displayOutput($response);
}
$result_exists = $db->query('SELECT * FROM pm_user WHERE  (mobile = ' . $phone . ')');
if ($result_exists !== false && $db->last_row_count() > 0) {
	$userrow = $result_exists->fetch();
	$otp_pass = getRandonOTP(4);
	//$userMobile = $countrycode.$phone;
	$userMobile = $phone;
	//$data['mobile'] = $userMobile;
	//$data['otp'] = $otp_pass;
	//$data['email'] = '';
	$optUpdate = $db->query('UPDATE pm_user SET otp = ' . $otp_pass . ' WHERE  (mobile = ' . $phone . ')');
	if ($optUpdate) {
		$message = $otp_pass . "  is the OTP to login into your Gupta Hotel account";
		if (httpPost($userMobile, $message)) {
			$checkSql = $db->query('SELECT * FROM pm_device WHERE user_id  = "' . $userrow['id'] . '" AND device_type = "' . $device_type . '"');
			if ($checkSql !== false && $db->last_row_count() > 0) {
				$updateDevice = $db->query('UPDATE pm_device SET fcm_token = "' . $fcm_token . '" WHERE user_id  = "' . $userrow['id'] . '" AND device_type = "' . $device_type . '"');
			} else {
				//Add Device Token
				$data = array();
				$data['id'] = null;
				$data['user_id'] = $userrow['id'];
				$data['device_type'] = $device_type;
				$data['fcm_token'] = $fcm_token;
				$insertDevice = db_prepareInsert($db, 'pm_device', $data);
				$insertDevice->execute();
				//Add Device Token
			}
			$response = array('status' => array('error_code' => 0, 'message' => 'OTP sent on mobile number for varification.'));
			displayOutput($response);
		} else {
			$response = array('status' => array('error_code' => 1, 'message' => 'SMS GATWAY NOT WORKING'));
			displayOutput($response);
		}
	} else {
		$response = array('status' => array('error_code' => 1, 'message' => 'Something is wrong. Please Try Again.'));
		displayOutput($response);
	}
} else {
	$response = array('status' => array('error_code' => 1, 'message' => 'Mobile does not Exist into System'));
	displayOutput($response);
}
displayOutput($response);
