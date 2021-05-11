<?php

/**
 * Script called on Hotel Details API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');
$response = array('status' => array('error_code' => 1, 'message' => 'You must update HMS App to latest version from play store to continue using it'));
//displayOutput($response);
//$uerId = htmlentities($_POST['uerId'], ENT_COMPAT, 'UTF-8');

$data = json_decode(file_get_contents('php://input'), true);

$userId = '';
if (isset($data['userId'])) {
	$userId = $data['userId'];
}
$hotelId = $data['hotelId'];

if (empty($hotelId)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Hotel Id is required'));
	displayOutput($response);
}


//$result_exists = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.address_2 as address, a.descr as description, b.discount as discountPercent, MIN(b.price) AS minBookingCost, round(b.price - ( b.discount/100 * b.price ),2) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = '.$hotelId.' AND user_id = '.$userId.'), "TRUE", "FALSE")  as is_fav  FROM pm_hotel a INNER JOIN pm_rate b ON a.id = b.id_hotel WHERE  (a.id = '.$hotelId.')');

//$result_exists = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, CONCAT(a.city, ", ", a.state) as address, a.descr as description, IF (MAX(b.discount), MAX(b.discount), "") as discountPercent, IF (MAX(b.discount), (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = '.$hotelId.') AND id_hotel = '.$hotelId.'), MIN(b.price)) AS minBookingCost, IF (MAX(b.discount), round((SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = '.$hotelId.') AND id_hotel = '.$hotelId.') - ( MAX(b.discount)/100 * (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = '.$hotelId.') AND id_hotel = '.$hotelId.') ),2), MIN(b.price)) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = '.$hotelId.' AND user_id = '.$userId.'), "TRUE", "FALSE")  as is_fav, c.name as accommodation_type FROM pm_hotel a INNER JOIN pm_rate b ON a.id = b.id_hotel INNER JOIN pm_accommodation c ON a.id_accommodation = c.id WHERE  (a.id = '.$hotelId.')');
//echo 'SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, IF (MAX(b.discount), MAX(b.discount), "") as discountPercent, IF (MAX(b.discount), (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $hotelId . ') AND id_hotel = ' . $hotelId . '), MIN(b.price)) AS minBookingCost, IF (MAX(b.discount), round((SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = \'' . $hotelId . '\') AND id_hotel = ' . $hotelId . ') - ( MAX(b.discount)/100 * (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $hotelId . ') AND id_hotel = ' . $hotelId . ') ),2), MIN(b.price)) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $hotelId . ' AND user_id = \'' . $userId . '\'), "TRUE", "FALSE")  as is_fav, c.name as accommodation_type FROM pm_hotel a INNER JOIN pm_rate b ON a.id = b.id_hotel INNER JOIN pm_accommodation c ON a.id_accommodation = c.id WHERE  (a.id = ' . $hotelId . ')'; die;

$result_exists = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, IF (MAX(b.discount), MAX(b.discount), "") as discountPercent, IF (MAX(b.discount), (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $hotelId . ') AND id_hotel = ' . $hotelId . '), MIN(b.price)) AS minBookingCost, IF (MAX(b.discount), round((SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = \'' . $hotelId . '\') AND id_hotel = ' . $hotelId . ') - ( MAX(b.discount)/100 * (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $hotelId . ') AND id_hotel = ' . $hotelId . ') ),2), MIN(b.price)) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $hotelId . ' AND user_id = \'' . $userId . '\'), "TRUE", "FALSE")  as is_fav, c.name as accommodation_type FROM pm_hotel a INNER JOIN pm_rate b ON a.id = b.id_hotel INNER JOIN pm_accommodation c ON a.id_accommodation = c.id WHERE  (a.id = ' . $hotelId . ')');

if ($result_exists !== false && $db->last_row_count() > 0) {

	$result = array();
	$row = $result_exists->fetch();

	$result_locaton = $db->query('SELECT lat as latitude, lng as longitude, city as address FROM pm_hotel WHERE (id = ' . $hotelId . ')');
	$row1 = $result_locaton->fetch();

	$facilities = array();
	$result_amenities = $db->query('SELECT facilities FROM pm_hotel WHERE (id = ' . $hotelId . ')');
	$row2 = $result_amenities->fetch();

	$facilities = explode(',', $row2['facilities']);
	$resultt = "'" . implode("', '", $facilities) . "'";

	$checkSql = $db->query("SELECT a.id as amenities_id, a.name, CONCAT('/medias/facility/big/', b.id,'/',b.file) as icon FROM pm_facility a INNER JOIN pm_facility_file b ON a.id = b.id_item WHERE a.id IN (" . $resultt . ")");
	$row3 = $checkSql->fetchAll();

	$result_policy = $db->query('SELECT cancel_policy, book_policy, general_policies as policy FROM pm_hotel WHERE (id = ' . $hotelId . ')');
	$row4 = $result_policy->fetch();

	$result_lowest = $db->query('SELECT MIN(a.price) AS price, b.max_people as capacity FROM pm_rate a INNER JOIN pm_room b ON a.id_room = b.id WHERE  (a.id_hotel = ' . $hotelId . ')');
	$row5 = $result_lowest->fetch();

	$result_image = $db->query('SELECT label as tag, CONCAT("/medias/hotel/medium/", id,"/",file) as name FROM pm_hotel_file WHERE (id_item = ' . $hotelId . ')');
	$row6 = $result_image->fetchAll();

	$result['basicDetails'] = $row;
	$result['location'] = $row1;
	$result['amenities'] = $row3;
	$result['policies'] = $row4;
	$result['lowestCostRoom'] = $row5;
	$result['images'] = $row6;

	$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array('hotel_details' => $result));
	displayOutput($response);
} else {

	$response = array('status' => array('error_code' => 1, 'message' => 'Hotel Not Found!'));
	displayOutput($response);
}
displayOutput($response);
