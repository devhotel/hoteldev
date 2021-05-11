<?php

/**
 * Script called on Wishlist API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');
$response = array('status' => array('error_code' => 1, 'message' => 'You must update Gupta Hotels App to latest version from play store to continue using it'));
displayOutput($response);
$data = json_decode(file_get_contents('php://input'), true);

$userId = $data['userId'];


if (empty($userId)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'User Id is required'));
	displayOutput($response);
}


$result_exists = $db->query('SELECT hotel_id FROM pm_wishlist WHERE user_id = "' . $userId . '"');

if ($result_exists !== false && $db->last_row_count() > 0) {

	$result = array();
	$row = $result_exists->fetchAll();
	$i = 0;
	foreach ($row as $row1) {

		$result_basic = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, b.discount as discountPercent, MIN(b.price) AS minBookingCost, round(b.price - ( b.discount/100 * b.price ),2) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $row1['hotel_id'] . ' AND user_id = ' . $userId . '), "TRUE", "FALSE")  as is_fav  FROM pm_hotel a LEFT JOIN pm_rate b ON a.id = b.id_hotel WHERE  (a.id = ' . $row1['hotel_id'] . ')');
		$rowBasic = $result_basic->fetch();

		$result_locaton = $db->query('SELECT lat as latitude, lng as longitude, city as address FROM pm_hotel WHERE (id = ' . $row1['hotel_id'] . ')');
		$rowLocation = $result_locaton->fetch();

		$facilities = array();
		$result_amenities = $db->query('SELECT facilities FROM pm_hotel WHERE (id = ' . $row1['hotel_id'] . ')');
		$row2 = $result_amenities->fetch();

		$facilities = explode(',', $row2['facilities']);
		$resultt = "'" . implode("', '", $facilities) . "'";

		$checkSql = $db->query("SELECT a.id as amenities_id, a.name, CONCAT('/medias/facility/big/', b.id,'/',b.file) as icon FROM pm_facility a INNER JOIN pm_facility_file b ON a.id = b.id_item WHERE a.id IN (" . $resultt . ")");
		$rowAmenities = $checkSql->fetchAll();

		$result_policy = $db->query('SELECT cancel_policy as policy FROM pm_hotel WHERE (id = ' . $row1['hotel_id'] . ')');
		$rowPolicies = $result_policy->fetch();

		$result_lowest = $db->query('SELECT MIN(a.price) AS price, b.max_people as capacity FROM pm_rate a LEFT JOIN pm_room b ON a.id_room = b.id WHERE  (a.id_hotel = ' . $row1['hotel_id'] . ')');
		$rowlowestCostRoom = $result_lowest->fetch();

		$result_image = $db->query('SELECT label as tag, CONCAT("/medias/hotel/medium/", id,"/",file) as name FROM pm_hotel_file WHERE (id_item = ' . $row1['hotel_id'] . ')');
		$rowImages = $result_image->fetchAll();


		$result[$i]['basicDetails'] = $rowBasic;
		$result[$i]['location'] = $rowLocation;
		$result[$i]['amenities'] = $rowAmenities;
		$result[$i]['policies'] = $rowPolicies;
		$result[$i]['lowestCostRoom'] = $rowlowestCostRoom;
		$result[$i]['images'] = $rowImages;

		$i++;
	}

	$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array("hotelList" => $result));
	displayOutput($response);
} else {

	$response = array('status' => array('error_code' => 1, 'message' => 'Success'), 'response' => array("message" => "There is no Hotel in Wishlist"));
	displayOutput($response);
}
displayOutput($response);
