<?php

/**
 * Script called on OTP validation API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');
$otp = htmlentities($_POST['otp'], ENT_COMPAT, 'UTF-8');
if (isset($_POST['mobile'])) {
	$mobile = htmlentities($_POST['mobile'], ENT_COMPAT, 'UTF-8');
} else {
	$mobile  = '';
}
if (empty($otp)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'OTP is required'));
	displayOutput($response);
}
if ($mobile != '') {
	$result_exists = $db->query('SELECT id, email, firstname, lastname, address, postcode, city, state, country, company, country_code as countryCode, mobile, otp FROM pm_user WHERE  (otp = ' . $otp . ') AND mobile = ' . $mobile . '');
} else {
	$result_exists = $db->query('SELECT id, email, firstname, lastname, address, postcode, city, state, country, company, country_code as countryCode, mobile, otp FROM pm_user WHERE  (otp = ' . $otp . ')');
}
if ($result_exists !== false && $db->last_row_count() > 0) {
	$row = $result_exists->fetch();
	if ($otp == $row['otp']) {

		$_SESSION['user']['id'] = $row['id'];
		$_SESSION['user']['login'] = 'Traveler';
		$_SESSION['user']['email'] = $row['email'];
		$_SESSION['user']['name'] = 'Traveler';
		$_SESSION['user']['type'] = 'registered';

		$response = array('status' => array('error_code' => 0, 'message' => 'Successfully Logged In'), 'response' => array('user' => $row));
		displayOutput($response);
	} else {

		$response = array('status' => array('error_code' => 1, 'message' => 'Something is wrong. Please Try Again.'));
		displayOutput($response);
	}
} else {

	$response = array('status' => array('error_code' => 1, 'message' => 'OTP is not Matched!'));
	displayOutput($response);
}
