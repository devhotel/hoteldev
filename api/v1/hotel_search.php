<?php

/**
 * Script called on Hotel Search API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');


$data = json_decode(file_get_contents('php://input'), true);
$userId = '';
//if (in_array('userId', $data)) {
if (isset($data['userId'])) {
	$userId = $data['userId'];
}
if (in_array('hotelId', $data)) {
	$hotelId = $data['hotelId'];
} else {
	$hotelId = '';
}
$locationId = $data['locationId'];
//$checkinDate = strtotime($data['checkinDate']) * 1000;
//$checkoutDate = strtotime($data['checkoutDate']) * 1000;
$checkinDate = strtotime($data['checkInDate']) + 86400;
$checkoutDate = strtotime($data['checkOutDate']) + 86400;
$rooms = $data['rooms'];
if (in_array('priceRange', $data)) {
	$priceRange = $data['priceRange'];
} else {
	$priceRange = ["1-10000"];
}
if (in_array('hotelId', $data)) {
	$hotelClass = $data['hotelClass'];
} else {
	$hotelClass = [1, 2, 3, 4, 5];
}
if (in_array('hotelId', $data)) {
	$accomodatation = $data['accomodatation'];
} else {
	$accomodatation = ["Hotel"];
}
//Hotel Class
$maxcVal = max($hotelClass);
$mincVal = min($hotelClass);
//Rooms
$roomCount = count($rooms);
$maxAdult = 0;
$maxChild = 0;
foreach ($rooms as $k => $v) {
	$maxAdult = max(array($maxAdult, $v['noOfAdult']));
	$maxChild = max(array($maxChild, $v['noOfChild']));
}
//Max Value From Price Range
$maxVal = 0;
foreach ($priceRange as $priceRan) {
	$maxVal = max(array($maxVal, $priceRan));
}
$maxArr = explode("-", $maxVal);
$maxFinal = max($maxArr);
//Min Value From Price Range
function getMin($array)
{
	$n = count($array);
	$min = $array[0];
	for ($i = 1; $i < $n; $i++)
		if ($min > $array[$i])
			$min = $array[$i];
	return $min;
}
$minVal = getMin($priceRange);
$minArr = explode("-", $minVal);
$minFinal = min($minArr);
/*if(empty($hotelId) || empty($locationId)){
  $response=array('status'=>array('error_code'=>1,'message'=>'Hotel Id or Location Id is required'));
  displayOutput($response);
} else {*/
if (!empty($hotelId)) {
	//$result_count = $db->query('SELECT count(d.id) as room_count FROM pm_booking_room d LEFT JOIN pm_booking e ON d.id_booking = e.id WHERE (("'.$checkinDate.'" between e.from_date and e.to_date) OR ("'.$checkoutDate.'" between e.from_date and e.to_date)) AND d.id_room = 33'); 
	//$rowCount = $result_count->fetchAll();
	//$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array('roomList'=>$rowCount));
	//displayOutput($response);
	//die;
	$sql = 'SELECT a.id as roomId, a.title as name, a.max_people as roomCapacity, b.price as bookingRate, IF (b.discount, b.discount, "") as discountPercent, IF (b.discount, round(b.price - ( b.discount/100 * b.price ),2), "") as discountedBookingRate, CONCAT("/medias/room/small/", c.id,"/",c.file) as roomImage, a.stock, (SELECT count(d.id) as room_count FROM pm_booking_room d LEFT JOIN pm_booking e ON d.id_booking = e.id WHERE (("' . $checkinDate . '" between e.from_date and e.to_date) OR ("' . $checkoutDate . '" between e.from_date and e.to_date)) AND d.id_room = a.id) as total_book, (a.stock - (SELECT count(d.id) as room_count FROM pm_booking_room d LEFT JOIN pm_booking e ON d.id_booking = e.id WHERE (("' . $checkinDate . '" between e.from_date and e.to_date) OR ("' . $checkoutDate . '" between e.from_date and e.to_date)) AND d.id_room = a.id)) as remain_book FROM pm_room a INNER JOIN pm_rate b ON a.id = b.id_room INNER JOIN pm_room_file c ON a.id = c.id_item WHERE a.id_hotel = "' . $hotelId . '" AND a.max_adults >= "' . $maxAdult . '" AND a.max_children >= "' . $maxChild . '"';
	$sql .= ' AND ((a.stock - (SELECT count(d.id) as room_count FROM pm_booking_room d LEFT JOIN pm_booking e ON d.id_booking = e.id WHERE (("' . $checkinDate . '" between e.from_date and e.to_date) OR ("' . $checkoutDate . '" between e.from_date and e.to_date)) AND d.id_room = a.id)) >= ' . $roomCount . ')';
	if (!empty($priceRange)) {
		$sql .= 'AND (b.price between "' . $minFinal . '" and "' . $maxFinal . '")';
	}
	$sql .= ' AND a.id NOT IN (SELECT id_hotel FROM pm_booking WHERE (("' . $checkinDate . '" between from_date and to_date) OR ("' . $checkoutDate . '" between from_date and to_date)) AND ((SELECT SUM(stock) as stock FROM pm_room) = (SELECT count(id) as booked_room_id FROM pm_booking_room )))';
	$result_exists = $db->query($sql);

	if ($result_exists !== false && $db->last_row_count() > 0) {

		$row = $result_exists->fetchAll();

		//$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array('hotel_details' => $result));
		$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array('roomList' => $row));
		displayOutput($response);
	} else {

		$response = array('status' => array('error_code' => 1, 'message' => 'Rooms Not Found!'));
		displayOutput($response);
	}
	displayOutput($response);
} else if (!empty($locationId)) {
	//$result_count = $db->query('SELECT count(d.id) as room_count FROM pm_booking_room d LEFT JOIN pm_booking e ON d.id_booking = e.id WHERE (("'.$checkinDate.'" between e.from_date and e.to_date) OR ("'.$checkoutDate.'" between e.from_date and e.to_date)) AND d.id_room = 33'); 
	//$rowCount = $result_count->fetchAll();
	//$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array('roomList'=>$rowCount));
	//displayOutput($response);
	//die;
	$sql = 'SELECT a.id as location_id, a.name, b.id as hotel_id, c.id as roomId, d.id as rate_id, d.price as room_price, e.name as accm_name FROM pm_destination a INNER JOIN pm_hotel b ON a.id = b.id_destination INNER JOIN pm_room c ON b.id = c.id_hotel INNER JOIN pm_rate d ON c.id = d.id_room INNER JOIN pm_accommodation e ON b.id_accommodation = e.id WHERE b.checked = 1 AND a.id = "' . $locationId . '" AND c.max_adults >= "' . $maxAdult . '" AND c.max_children >= "' . $maxChild . '"';
	//$sql .=' AND ((c.stock - (SELECT count(id) FROM pm_booking_room WHERE id_room = c.id)) >= '.$roomCount.')'; Remain Count Check
	$sql .= ' AND ((c.stock - (SELECT count(d.id) as room_count FROM pm_booking_room d LEFT JOIN pm_booking e ON d.id_booking = e.id WHERE (("' . $checkinDate . '" between e.from_date and e.to_date) OR ("' . $checkoutDate . '" between e.from_date and e.to_date)) AND d.id_room = c.id)) >= ' . $roomCount . ')';
	if (!empty($priceRange)) {
		$sql .= 'AND (d.price between "' . $minFinal . '" and "' . $maxFinal . '")';
	}
	if (!empty($hotelClass)) {
		$sql .= 'AND (b.class between "' . $mincVal . '" and "' . $maxcVal . '")';
	}
	if (!empty($accomodatation)) {
		$sql .= 'AND e.name IN ("' . implode('", "', $accomodatation) . '")';
	}
	$sql .= ' AND b.id NOT IN (SELECT id_hotel FROM pm_booking WHERE (("' . $checkinDate . '" between from_date and to_date) OR ("' . $checkoutDate . '" between from_date and to_date)) AND ((SELECT SUM(stock) as stock FROM pm_room) = (SELECT count(id) as booked_room_id FROM pm_booking_room ))) GROUP BY b.id';
	$result_exists = $db->query($sql);
	if ($result_exists !== false && $db->last_row_count() > 0) {
		$result = array();
		$row = $result_exists->fetchAll();
		$i = 0;
		foreach ($row as $row1) {
			//$result_basic = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, CONCAT(a.city, ", ", a.state) as address, a.descr as description, IF (MAX(b.discount), MAX(b.discount), "") as discountPercent, IF (MAX(b.discount), (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = '.$row1['hotel_id'].') AND id_hotel = '.$row1['hotel_id'].'), MIN(b.price)) AS minBookingCost, IF (MAX(b.discount), round((SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = '.$row1['hotel_id'].') AND id_hotel = '.$row1['hotel_id'].') - ( MAX(b.discount)/100 * (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = '.$row1['hotel_id'].') AND id_hotel = '.$row1['hotel_id'].') ),2), MIN(b.price)) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = '.$row1['hotel_id'].' AND user_id = '.$userId.'), "TRUE", "FALSE")  as is_fav  FROM pm_hotel a LEFT JOIN pm_rate b ON a.id = b.id_hotel WHERE  (a.id = '.$row1['hotel_id'].')');
			//if ($userId != '') {
			//$result_basic = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, IF (MAX(b.discount), MAX(b.discount), "") as discountPercent, IF (MAX(b.discount), (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . '), MIN(b.price)) AS minBookingCost, IF (MAX(b.discount), round((SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . ') - ( MAX(b.discount)/100 * (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . ') ),2), MIN(b.price)) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $row1['hotel_id'] . ' AND user_id = \'' . $userId . '\'), "TRUE", "FALSE")  as is_fav, c.name as accommodation_type FROM pm_hotel a LEFT JOIN pm_rate b ON a.id = b.id_hotel INNER JOIN pm_accommodation c ON a.id_accommodation = c.id WHERE (a.id = ' . $row1['hotel_id'] . ')');
			//$result_basic = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description,IF (b.discount_type, b.discount_type, "fixed") as discountType, IF (MAX(b.discount), MAX(b.discount), "") as discountPercent, IF (MAX(b.discount), (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . '), MIN(b.price)) AS minBookingCost, IF (MAX(b.discount), round((SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . ') - ( MAX(b.discount)/100 * (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . ') ),2), MIN(b.price)) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $row1['hotel_id'] . ' AND user_id = \'' . $userId . '\'), "TRUE", "FALSE")  as is_fav, c.name as accommodation_type, (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . ') as Rate FROM pm_hotel a LEFT JOIN pm_rate b ON a.id = b.id_hotel INNER JOIN pm_accommodation c ON a.id_accommodation = c.id WHERE (a.id = ' . $row1['hotel_id'] . ')');
			$result_basic = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, IF (MAX(b.discount), MAX(b.discount), "") as discountPercent, IF (b.discount_type, b.discount_type, "fixed") as discountType, IF (MAX(b.discount), (SELECT MIN(price) FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . '), MIN(b.price)) AS minBookingCost, IF (MAX(b.discount), round((SELECT MIN(price) FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = \'' . $row1['hotel_id'] . '\') AND id_hotel = ' . $row1['hotel_id'] . ') - ( MAX(b.discount)/100 * (SELECT MIN(price) FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . ') ),2), MIN(b.price)) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $row1['hotel_id'] . ' AND user_id = \'' . $userId . '\'), "TRUE", "FALSE")  as is_fav, c.name as accommodation_type, (SELECT MIN(price) FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . ') as Rate FROM pm_hotel a INNER JOIN pm_rate b ON a.id = b.id_hotel INNER JOIN pm_accommodation c ON a.id_accommodation = c.id WHERE  (a.id = ' . $row1['hotel_id'] . ')');
			//} else {
			//$result_basic = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, IF (MAX(b.discount), MAX(b.discount), "") as discountPercent, IF (MAX(b.discount), (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . '), MIN(b.price)) AS minBookingCost, IF (MAX(b.discount), round((SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . ') - ( MAX(b.discount)/100 * (SELECT price FROM pm_rate WHERE discount = (SELECT MAX(discount) FROM pm_rate WHERE id_hotel = ' . $row1['hotel_id'] . ') AND id_hotel = ' . $row1['hotel_id'] . ') ),2), MIN(b.price)) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $row1['hotel_id'] . '), "TRUE", "FALSE")  as is_fav, c.name as accommodation_type FROM pm_hotel a LEFT JOIN pm_rate b ON a.id = b.id_hotel INNER JOIN pm_accommodation c ON a.id_accommodation = c.id WHERE (a.id = ' . $row1['hotel_id'] . ')');
			//}
			//$result_basic = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.address_2 as address, a.descr as description, MAX(b.discount) as discountPercent, b.price AS minBookingCost, round(b.price - ( b.discount/100 * b.price ),2) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = '.$row1['hotel_id'].' AND user_id = '.$userId.'), "TRUE", "FALSE")  as is_fav  FROM pm_hotel a LEFT JOIN pm_rate b ON a.id = b.id_hotel WHERE  (a.id = '.$row1['hotel_id'].')');
			$tmprowBasic = $result_basic->fetch();
			//$rowBasic = $result_basic->fetch();
			$rowBasic['hotel_id'] 			= $tmprowBasic['hotel_id'];
			$rowBasic['name'] 				= $tmprowBasic['name'];
			$rowBasic['numberOfStar'] 		= $tmprowBasic['numberOfStar'];
			$rowBasic['address'] 			= $tmprowBasic['address'];
			$rowBasic['description'] 		= $tmprowBasic['description'];
			// $rowBasic['discountPercent'] 	= $tmprowBasic['discountPercent'];
			// $rowBasic['minBookingCost'] 	= $tmprowBasic['minBookingCost'];
			// $rowBasic['discountAmout'] 		= (string) number_format((float)($tmprowBasic['discountPercent'] != '' ? ($tmprowBasic['discountType'] == 'fixed' ? $tmprowBasic['Rate'] - $tmprowBasic['discountPercent'] : $tmprowBasic['Rate'] - (($tmprowBasic['Rate'] * $tmprowBasic['discountPercent']) / 100)) : $tmprowBasic['Rate']), 2, '.', '');
			$rowBasic['is_fav'] 			= $tmprowBasic['is_fav'];
			$rowBasic['accommodation_type']	= $tmprowBasic['accommodation_type'];
			$discountPercent = '';
			$minBookingCost = '';
			$discountAmout = '';
			$rateResQ = $db->query("SELECT price, discount, discount_type from pm_rate where id_hotel = '" . $row1['hotel_id'] . "'");
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
			$rowBasic['discountPercent']	= $discountPercent;
			$rowBasic['minBookingCost'] 	= (string) number_format((float) $minBookingCost, 2, '.', '');
			$rowBasic['discountAmout'] 		= (string) number_format((float) $discountAmout, 2, '.', '');
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
		$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array('recommendedHotelList' => $result));
		displayOutput($response);
	} else {

		$response = array('status' => array('error_code' => 1, 'message' => 'Hotels Not Found!'));
		displayOutput($response);
	}
	displayOutput($response);
}
//}
displayOutput($response);
