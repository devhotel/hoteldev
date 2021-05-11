<?php

/**
 * Script called on Recomended API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');
//$response = array("status" => array(), "response" => array());
$data = json_decode(file_get_contents('php://input'), true);

//$userId = htmlentities($_POST['userId'], ENT_COMPAT, 'UTF-8');
//$latitude = htmlentities($_POST['latitude'], ENT_COMPAT, 'UTF-8');
//$longitude = htmlentities($_POST['longitude'], ENT_COMPAT, 'UTF-8');
$userId = '';
//if (in_array('userId', $data)) {
if (!empty($data)) {
	$userId = $data['userId'];
}
//$latitude = $data['latitude'];
//$longitude = $data['longitude'];

// if(empty($userId)){
//   $response=array('status'=>array('error_code'=>1,'message'=>'User Id is required'));
//   displayOutput($response);
// }

/*if(empty($latitude)){
  $response=array('status'=>array('error_code'=>1,'message'=>'User Latitute is required'));
  displayOutput($response);
}

if(empty($longitude)){
  $response=array('status'=>array('error_code'=>1,'message'=>'User Longitude is required'));
  displayOutput($response);
}*/


//$result_exists = $db->query('SELECT id as hotel_id FROM pm_hotel WHERE lat = "'.$latitude.'" AND lng = "'.$longitude.'" AND id NOT IN (SELECT id_hotel FROM pm_booking WHERE (from_date > unix_timestamp() OR to_date < unix_timestamp() ) AND ((SELECT SUM(stock) as stock FROM pm_room) = (SELECT count(id) as booked_room_id FROM pm_booking_room )))');

//$result_exists = $db->query('SELECT a.title as name, a.class as numberOfStar, a.address_2 as address, a.descr as description, b.discount as discountPercent, MIN(b.price) AS minBookingCost, round(b.price - ( b.discount/100 * b.price ),2) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = a.id AND user_id = '.$userId.'), "TRUE", "FALSE")  as is_fav FROM pm_hotel a INNER JOIN pm_rate b ON a.id = b.id_hotel WHERE a.lat = "'.$latitude.'" AND a.lng = "'.$longitude.'" AND a.id NOT IN (SELECT id_hotel FROM pm_booking WHERE (from_date > unix_timestamp() OR to_date < unix_timestamp() ) AND ((SELECT SUM(stock) as stock FROM pm_room) = (SELECT count(id) as booked_room_id FROM pm_booking_room )))');

/*$result_exists = $db->query('SELECT id as hotel_id FROM (
        SELECT *, 
            (
                (
                    (
                        acos(
                            sin(( "'.$latitude.'" * pi() / 180))
                            *
                            sin(( `lat` * pi() / 180)) + cos(( "'.$latitude.'" * pi() /180 ))
                            *
                            cos(( `lat` * pi() / 180)) * cos((( "'.$longitude.'" - `lng`) * pi()/180)))
                    ) * 180/pi()
                ) * 60 * 1.1515 * 1.609344
            )
        as distance FROM `pm_hotel`
    ) pm_hotel
    WHERE distance <= 1000 AND id NOT IN (SELECT id_hotel FROM pm_booking WHERE (from_date > unix_timestamp() OR to_date < unix_timestamp() ) AND ((SELECT SUM(stock) as stock FROM pm_room) = (SELECT count(id) as booked_room_id FROM pm_booking_room ))) AND id IN (SELECT id_hotel FROM pm_room)');*/

$result_exists = $db->query('SELECT id as hotel_id FROM pm_hotel WHERE home = 1 AND id NOT IN (SELECT id_hotel FROM pm_booking WHERE (from_date > unix_timestamp() OR to_date < unix_timestamp() ) AND ((SELECT SUM(stock) as stock FROM pm_room) = (SELECT count(id) as booked_room_id FROM pm_booking_room ))) AND id IN (SELECT id_hotel FROM pm_room)');

if ($result_exists !== false && $db->last_row_count() > 0) {

	$result = array();
	$row = $result_exists->fetchAll();
	$i = 0;
	foreach ($row as $row1) {
		//$result_basic = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, CONCAT(a.city, ", ", a.state) as address, a.descr as description, b.discount as discountPercent, MIN(b.price) AS minBookingCost, round(b.price - ( b.discount/100 * b.price ),2) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = '.$row1['hotel_id'].' AND user_id = '.$userId.'), "TRUE", "FALSE")  as is_fav  FROM pm_hotel a LEFT JOIN pm_rate b ON a.id = b.id_hotel WHERE  (a.id = '.$row1['hotel_id'].')');
		//if ($userId != '') {
		// echo 'SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, b.discount as discountPercent, MIN(b.price) AS minBookingCost, round(b.price - ( b.discount/100 * b.price ),2) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $row1['hotel_id'] . ' AND user_id = \'' . $userId . '\'), "TRUE", "FALSE")  as is_fav, c.name as accommodation_type  FROM pm_hotel a LEFT JOIN pm_rate b ON a.id = b.id_hotel INNER JOIN pm_accommodation c ON a.id_accommodation = c.id WHERE  (a.id = ' . $row1['hotel_id'] . ')';
		// die;
		//$result_basic = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, b.discount as discountPercent, MIN(b.price) AS minBookingCost, round(b.price - ( b.discount/100 * b.price ),2) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $row1['hotel_id'] . ' AND user_id = \'' . $userId . '\'), "TRUE", "FALSE")  as is_fav, c.name as accommodation_type  FROM pm_hotel a LEFT JOIN pm_rate b ON a.id = b.id_hotel INNER JOIN pm_accommodation c ON a.id_accommodation = c.id WHERE  (a.id = ' . $row1['hotel_id'] . ')');
		$result_basic = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, b.discount_type as discountType, MIN(b.price) as Rate, b.discount as discountPercent, MIN(b.price) AS minBookingCost, round(b.price - ( b.discount/100 * b.price ),2) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $row1['hotel_id'] . ' AND user_id = \'' . $userId . '\'), "TRUE", "FALSE")  as is_fav, c.name as accommodation_type  FROM pm_hotel a LEFT JOIN pm_rate b ON a.id = b.id_hotel INNER JOIN pm_accommodation c ON a.id_accommodation = c.id WHERE  (a.id = ' . $row1['hotel_id'] . ')');
		//} else {
		//$result_basic = $db->query('SELECT a.id as hotel_id, a.title as name, a.class as numberOfStar, a.city as address, a.descr as description, b.discount as discountPercent, MIN(b.price) AS minBookingCost, round(b.price - ( b.discount/100 * b.price ),2) as discountAmout, IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = ' . $row1['hotel_id'] . '), "TRUE", "FALSE")  as is_fav, c.name as accommodation_type  FROM pm_hotel a LEFT JOIN pm_rate b ON a.id = b.id_hotel INNER JOIN pm_accommodation c ON a.id_accommodation = c.id WHERE  (a.id = ' . $row1['hotel_id'] . ')');
		//}
		//$rowBasic = $result_basic->fetch();


		$tmprowBasic = $result_basic->fetch();
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
	$response = array('status' => array('error_code' => 0, 'message' => 'Get Hotels'), 'response' => array('hotel_details' => $result));
	displayOutput($response);
} else {
	$response = array('status' => array('error_code' => 1, 'message' => 'Hotel Not Found!'));
	displayOutput($response);
}
displayOutput($response);