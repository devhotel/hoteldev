<?php

/**
 * Script called on Hotel Details API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');
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
//$result_exists = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, IF (MAX(b.discount), MAX(b.discount), "") as discountPercent, IF (b.discount_type, b.discount_type, "fixed") as discountType, IF (MAX(b.discount), (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $hotelId . ') AND id_hotel = ' . $hotelId . '), MIN(b.price)) AS minBookingCost, IF (MAX(b.discount), round((SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = \'' . $hotelId . '\') AND id_hotel = ' . $hotelId . ') - ( MAX(b.discount)/100 * (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $hotelId . ') AND id_hotel = ' . $hotelId . ') ),2), MIN(b.price)) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $hotelId . ' AND user_id = \'' . $userId . '\'), "TRUE", "FALSE")  as is_fav, c.name as accommodation_type, (SELECT MIN(price) FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $hotelId . ') AND id_hotel = ' . $hotelId . ') as Rate FROM pm_hotel a INNER JOIN pm_rate b ON a.id = b.id_hotel INNER JOIN pm_accommodation c ON a.id_accommodation = c.id WHERE  (a.id = ' . $hotelId . ')');

// echo 'SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, 
// IF (MAX(b.discount), MAX(b.discount), "") as discountPercent, 
// IF (b.discount_type, b.discount_type, "fixed") as discountType, 
// IF (MAX(b.discount), 
// (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate 
// WHERE id_hotel = ' . $hotelId . ') AND id_hotel = ' . $hotelId . '), MIN(b.price)) AS minBookingCost, 
// IF (MAX(b.discount), round((SELECT price FROM pm_rate 
// WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = \'' . $hotelId . '\') AND id_hotel = ' . $hotelId . ') - 
// ( MAX(b.discount)/100 * (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $hotelId . ') 
// AND id_hotel = ' . $hotelId . ') ),2), MIN(b.price)) as discountAmout, 
// IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $hotelId . ' AND user_id = \'' . $userId . '\'), "TRUE", "FALSE")  as is_fav,
//  c.name as accommodation_type, (SELECT MIN(price) FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate 
//  WHERE id_hotel = ' . $hotelId . ') AND id_hotel = ' . $hotelId . ') as Rate FROM pm_hotel a 
//  INNER JOIN pm_rate b ON a.id = b.id_hotel 
//  INNER JOIN pm_accommodation c ON a.id_accommodation = c.id 
//  WHERE  (a.id = ' . $hotelId . ')'; die;

$result_exists = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, 
IF (MAX(b.discount), MAX(b.discount), "") as discountPercent, 
IF (b.discount_type, b.discount_type, "fixed") as discountType, 
IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $hotelId . ' AND user_id = \'' . $userId . '\'), "TRUE", "FALSE")  as is_fav,
 c.name as accommodation_type, (SELECT MIN(price) FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate 
 WHERE id_hotel = ' . $hotelId . ') AND id_hotel = ' . $hotelId . ') as Rate FROM pm_hotel a 
 INNER JOIN pm_rate b ON a.id = b.id_hotel 
 INNER JOIN pm_accommodation c ON a.id_accommodation = c.id 
 WHERE  (a.id = ' . $hotelId . ')');

if ($result_exists !== false && $db->last_row_count() > 0) {
	$result = array();
	$tmpRow = $result_exists->fetch();
	$row = array();
	$row['hotel_id'] 			= $tmpRow['hotel_id'];
	$row['name'] 				= $tmpRow['name'];
	$row['numberOfStar'] 		= $tmpRow['numberOfStar'];
	$row['address'] 			= $tmpRow['address'];
	$row['description'] 		= $tmpRow['description'];
	//$row['discountPercent'] 	= $tmpRow['discountPercent'];
	//$row['discountType'] 		= $tmpRow['discountType'];
	//$row['minBookingCost'] 		= $tmpRow['minBookingCost'];
	//$row['discountAmout'] 		= (string) number_format((float)($tmpRow['discountPercent'] != '' ? ($tmpRow['discountType'] == 'fixed' ? $tmpRow['Rate'] - $tmpRow['discountPercent'] : $tmpRow['Rate'] - (($tmpRow['Rate'] *$tmpRow['discountPercent']) / 100)) : $tmpRow['Rate']), 2, '.', '');
	$row['is_fav'] 				= $tmpRow['is_fav'];
	$row['accommodation_type'] 	= $tmpRow['accommodation_type'];
	$discountPercent = '';
	$minBookingCost = '';
	$discountAmout = '';
	$rateResQ = $db->query("SELECT price, discount, discount_type from pm_rate where id_hotel = '" . $hotelId . "'");
	if ($rateResQ !== false && $db->last_row_count() > 0) {
		$rateRes = $rateResQ->fetchAll();
		foreach($rateRes as $k => $p){					
			$oldDiscountPercent 	= ($p['discount'] != '' ? ($p['discount'] != '0' ? $p['discount'] : "") : "");
			$oldMinBookingCost 		= $p['price'];
			if($p['discount'] != '' || $p['discount'] != 0){
				if($p['discount_type'] == 'fixed'){
					$oldDiscountAmout 		= $p['price'] - $p['discount'];
				}elseif($p['discount_type'] == 'rate'){
					$oldDiscountAmout 		= $p['price'] - (($p['price'] * $p['discount']) / 100);
				}else{
					$oldDiscountPercent = "";
					$oldDiscountAmout 		= $p['price'];
				}
			}else{
				$oldDiscountAmout 		= $p['price'];
			}
			if($minBookingCost == ''){
				$discountPercent 	= $oldDiscountPercent;
				$minBookingCost 	= $oldMinBookingCost;
				$discountAmout 		= $oldDiscountAmout;
			}else{
				if($oldDiscountAmout < $discountAmout){
					$discountPercent 	= $oldDiscountPercent;
					$minBookingCost 	= $oldMinBookingCost;
					$discountAmout 		= $oldDiscountAmout;
				}
			}
		}
	}
	$row['discountPercent'] 	= $discountPercent;
	$row['minBookingCost'] 		= (string) number_format((float) $minBookingCost, 2, '.', '');
	$row['discountAmout'] 		= (string) number_format((float) $discountAmout, 2, '.', '');
	$result_locaton = $db->query('SELECT lat as latitude, lng as longitude, city as address FROM pm_hotel WHERE (id = ' . $hotelId . ')');
	$row1 = $result_locaton->fetch();
	$facilities = array();
	$result_amenities = $db->query('SELECT facilities FROM pm_hotel WHERE (id = ' . $hotelId . ')');
	$row2 = $result_amenities->fetch();
	$facilities = explode(',', $row2['facilities']);
	$resultt = "'" . implode("', '", $facilities) . "'";
	$checkSql = $db->query("SELECT a.id as amenities_id, a.name, CONCAT('/medias/facility/big/', b.id,'/',b.file) as icon FROM pm_facility a INNER JOIN pm_facility_file b ON a.id = b.id_item WHERE a.id IN (" . $resultt . ")");
	$row3 = $checkSql->fetchAll();
	$result_policy = $db->query('SELECT cancel_policy as policy FROM pm_hotel WHERE (id = ' . $hotelId . ')');
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