<?php
/**
 * Script called on Hotel Room List API
 */
require_once('../../common/lib.php');
require_once('../../common/define.php');
$data = json_decode(file_get_contents('php://input'), true);
$hotelId = $data['hotelId'];
$checkinDate = strtotime($data['checkInDate']) + 19800;
//$checkoutDate = strtotime($data['checkOutDate']);
$checkoutDate = strtotime($data['checkOutDate']) + 19800;
$rooms = $data['rooms'];
//$noOfAdult = $data['noOfAdult'];
//$noOfChild = $data['noOfChild'];
if (in_array('priceRange', $data)) {
	$priceRange = $data['priceRange'];
} else {
	$priceRange = ["1-500000"];
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
//Rooms
$roomCount = count($rooms);
$maxAdult = 0;
$maxChild = 0;
foreach ($rooms as $k => $v) {
	$maxAdult = max(array($maxAdult, $v['noOfAdult']));
	$maxChild = max(array($maxChild, $v['noOfChild']));
}
if (empty($hotelId)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Hotel Id is required'));
	displayOutput($response);
}
$sql = 'SELECT a.id as roomId, a.title as name, a.max_people as roomCapacity, a.descr as room_description, b.price as bookingRate,
CASE
	WHEN b.discount_type = "fixed" THEN b.discount 
    WHEN b.discount_type = "rate" THEN b.discount
    WHEN b.discount_type = "" THEN ""    
END AS discountPercent, ';
if ($maxAdult == 1 && $maxChild == 0) {
	//$sql .= ' round(b.price - ( b.discount/100 * b.price ),2) as discountedBookingRate,';
	$sql .= " CASE
	WHEN b.discount_type = 'fixed' THEN
    (b.price - b.discount)    
    WHEN b.discount_type = 'rate' THEN
	(round(b.price - ( b.discount/100 * b.price ),2)) 
    WHEN b.discount_type = '' THEN
	(round(b.price,2)) 
END AS discountedBookingRate,";
} else if ($maxAdult == 1 && $maxChild > 0) {
	//$sql .= ' round(((b.price - ( b.discount/100 * b.price )) + ( ((SELECT MAX(child_price) FROM pm_rate WHERE id_room = a.id) * "' . $maxChild . '") /100 * (b.price - ( b.discount/100 * b.price )))),2) as discountedBookingRate,';
	$sql .= " CASE
	WHEN b.discount_type = 'fixed' THEN
    round(((b.price - b.discount) + ( ((SELECT MAX(child_price) FROM pm_rate WHERE id_room = a.id) * '" . $maxChild . "') /100 * (b.price - b.discount))),2) 
    WHEN b.discount_type = 'rate' THEN
	round(((b.price - ( b.discount/100 * b.price )) + ( ((SELECT MAX(child_price) FROM pm_rate WHERE id_room = a.id) * '" . $maxChild . "') /100 * (b.price - ( b.discount/100 * b.price )))),2)
    WHEN b.discount_type = '' THEN
	round(((b.price) + ( ((SELECT MAX(child_price) FROM pm_rate WHERE id_room = a.id) * '" . $maxChild . "') /100 * (b.price))),2)
END AS discountedBookingRate,";
} else if ($maxAdult > 1 && $maxChild == 0) {
	//$sql .= ' round(((b.price - ( b.discount/100 * b.price )) + ( (((SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id) * "' . $maxAdult . '") - (SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id)) /100 * (b.price - ( b.discount/100 * b.price )))),2) as discountedBookingRate,';
	$sql .= " CASE
	WHEN b.discount_type = 'fixed' THEN
    round(((b.price - b.discount) + ( (((SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id) * '" . $maxAdult . "') - (SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id)) /100 * (b.price - b.discount))),2) 
    WHEN b.discount_type = 'rate' THEN
	round(((b.price - ( b.discount/100 * b.price )) + ( (((SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id) * '" . $maxAdult . "') - (SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id)) /100 * (b.price - ( b.discount/100 * b.price )))),2) 
    WHEN b.discount_type = '' THEN
	round((b.price + ( (((SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id) * '" . $maxAdult . "') - (SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id)) /100 * (b.price))),2) 
END AS discountedBookingRate,";
} else if ($maxAdult > 1 && $maxChild > 0) {
	//$sql .= ' round(((b.price - ( b.discount/100 * b.price )) + ( ((((SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id) * "' . $maxAdult . '") - (SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id)) + ((SELECT MAX(child_price) FROM pm_rate WHERE id_room = a.id) * "' . $maxChild . '")) /100 * (b.price - ( b.discount/100 * b.price )))),2) as discountedBookingRate,';
	$sql .= " CASE
	WHEN b.discount_type = 'fixed' THEN
    round(((b.price - b.discount) + ( ((((SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id) * '" . $maxAdult . "') - (SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id)) + ((SELECT MAX(child_price) FROM pm_rate WHERE id_room = a.id) * '" . $maxChild . "')) /100 * (b.price - b.discount))),2) 
    WHEN b.discount_type = 'rate' THEN
	round(((b.price - ( b.discount/100 * b.price )) + ( ((((SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id) * '" . $maxAdult . "') - (SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id)) + ((SELECT MAX(child_price) FROM pm_rate WHERE id_room = a.id) * '" . $maxChild . "')) /100 * (b.price - ( b.discount/100 * b.price )))),2)
    WHEN b.discount_type = '' THEN
	round((b.price + ( ((((SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id) * '" . $maxAdult . "') - (SELECT MAX(price_sup) FROM pm_rate WHERE id_room = a.id)) + ((SELECT MAX(child_price) FROM pm_rate WHERE id_room = a.id) * '" . $maxChild . "')) /100 * b.price)),2)
END AS discountedBookingRate,";
}
$sql .= ' CONCAT("/medias/room/small/", c.id,"/",c.file) as roomImage, a.stock, (SELECT count(d.id) as room_count FROM pm_booking_room d LEFT JOIN pm_booking e ON d.id_booking = e.id WHERE (("' . $checkinDate . '" between e.from_date and e.to_date) OR ("' . $checkoutDate . '" between e.from_date and e.to_date)) AND d.id_room = a.id) as total_book, (a.stock - (SELECT count(d.id) as room_count FROM pm_booking_room d LEFT JOIN pm_booking e ON d.id_booking = e.id WHERE (("' . $checkinDate . '" between e.from_date and e.to_date) OR ("' . $checkoutDate . '" between e.from_date and e.to_date)) AND d.id_room = a.id)) as remain_book FROM pm_room a INNER JOIN pm_rate b ON a.id = b.id_room LEFT JOIN pm_room_file c ON a.id = c.id_item WHERE a.id_hotel = "' . $hotelId . '" AND a.max_adults >= "' . $maxAdult . '" AND a.max_children >= "' . $maxChild . '"';
$sql .= ' AND ((a.stock - (SELECT count(d.id) as room_count FROM pm_booking_room d LEFT JOIN pm_booking e ON d.id_booking = e.id WHERE (("' . $checkinDate . '" between e.from_date and e.to_date) OR ("' . $checkoutDate . '" between e.from_date and e.to_date)) AND d.id_room = a.id)) >= ' . $roomCount . ')'; //Remain The Count Chaecking
if (!empty($priceRange)) {
	$sql .= "AND 
	(
		CASE
		WHEN  
		b.discount_type = 'fixed' 
		THEN
		((b.price - b.discount) between '" . $minFinal . "' and '" . $maxFinal . "')
		WHEN b.discount_type = 'rate' THEN
		(round(b.price - ( b.discount/100 * b.price ),2) between '" . $minFinal . "' and '" . $maxFinal . "') 
		WHEN b.discount_type = '' THEN
		(round(b.price,2) between '" . $minFinal . "' and '" . $maxFinal . "') 
		END)";
}
$sql .= ' AND a.id NOT IN (SELECT id_hotel FROM pm_booking WHERE (("' . $checkinDate . '" between from_date and to_date) OR ("' . $checkoutDate . '" between from_date and to_date)) AND ((SELECT SUM(stock) as stock FROM pm_room) = (SELECT count(id) as booked_room_id FROM pm_booking_room ))) GROUP BY a.id ORDER BY IF (b.discount, discountedBookingRate, b.price) ASC';
// echo $sql;
// die;
$result_exists = $db->query($sql);
if ($result_exists !== false && $db->last_row_count() > 0) {
	$row = $result_exists->fetchAll();
	$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array('roomList' => $row));
	displayOutput($response);
} else {
	$response = array('status' => array('error_code' => 1, 'message' => 'Rooms Not Found!'));
	displayOutput($response);
}
displayOutput($response);
