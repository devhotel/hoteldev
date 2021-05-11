<?php

/**
 * Script called on Hotel Filter Suggestion API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');
$response = array('status' => array('error_code' => 1, 'message' => 'You must update HMS App to latest version from play store to continue using it'));
//displayOutput($response);

$result_exists = $db->query('SELECT id as hotel_id, title as name FROM pm_hotel WHERE checked = 1');

if ($result_exists !== false && $db->last_row_count() > 0) {

	$result = array();
	$row = $result_exists->fetchAll();

	//$result_locaton = $db->query('SELECT lat as latitude, lng as longitude, address_2 as address FROM pm_hotel');
	$result_locaton = $db->query('SELECT id, name FROM pm_destination');
	$row1 = $result_locaton->fetchAll();


	$result['hotel'] = $row;
	$result['location'] = $row1;

	$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array('list' => $result));
	displayOutput($response);
} else {

	$response = array('status' => array('error_code' => 1, 'message' => 'Hotel Not Found!'));
	displayOutput($response);
}
displayOutput($response);
