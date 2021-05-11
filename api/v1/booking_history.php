<?php

/**
 * Script called on Hotel Booking History API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');
$response = array('status' => array('error_code' => 1, 'message' => 'You must update Gupta Hotels App to latest version from play store to continue using it'));
displayOutput($response);
//$uerId = htmlentities($_POST['uerId'], ENT_COMPAT, 'UTF-8');

$data = json_decode(file_get_contents('php://input'), true);

//$userId = $data['userId'];
$userId = $data['userId'];

if (empty($userId)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'User Id is required'));
	displayOutput($response);
}


$result_exists = $db->query('SELECT id as booking_id, id_hotel as hotel_id FROM pm_booking WHERE id_user = "' . $userId . '"');

if ($result_exists !== false && $db->last_row_count() > 0) {

	//$row = $result_exists->fetchAll();
	$result = array();

	$result_completed = $db->query('SELECT a.id as booking_id, a.id_hotel as hotel_id, a.nights, a.adults, a.children, FROM_UNIXTIME((a.from_date + 19800),"%Y-%m-%d") as checkedIn_date, FROM_UNIXTIME((a.to_date + 19800),"%Y-%m-%d") as checkedOut_date, a.amount as subtotal, a.discount as discount_amount, a.ex_tax as extax_amount, a.tax_amount as tax_amount, a.total as booking_amount, b.name as booking_location, c.title as hotel_name, d.label as tag, CONCAT("/medias/hotel/medium/", d.id,"/",d.file) as hotel_image, (SELECT COUNT(id_booking) FROM pm_booking_room WHERE id_booking = a.id) as rooms FROM pm_booking a INNER JOIN pm_destination b ON a.id_destination = b.id INNER JOIN pm_hotel c ON a.id_hotel = c.id LEFT JOIN pm_hotel_file d ON a.id_hotel = d.id_item WHERE a.id_user = "' . $userId . '" AND a.status = 4 AND ((a.to_date+19800) < unix_timestamp()) GROUP BY a.id DESC');

	$rowCompleted = $result_completed->fetchAll();

	$result_cancelled = $db->query('SELECT a.id as booking_id, a.id_hotel as hotel_id, a.nights, a.adults, a.children, FROM_UNIXTIME((a.from_date + 19800),"%Y-%m-%d") as checkedIn_date, FROM_UNIXTIME((a.to_date + 19800),"%Y-%m-%d") as checkedOut_date, a.amount as subtotal, a.discount as discount_amount, a.ex_tax as extax_amount, a.tax_amount as tax_amount, a.total as booking_amount, b.name as booking_location, c.title as hotel_name, d.label as tag, CONCAT("/medias/hotel/medium/", d.id,"/",d.file) as hotel_image, (SELECT COUNT(id_booking) FROM pm_booking_room WHERE id_booking = a.id) as rooms FROM pm_booking a INNER JOIN pm_destination b ON a.id_destination = b.id INNER JOIN pm_hotel c ON a.id_hotel = c.id LEFT JOIN pm_hotel_file d ON a.id_hotel = d.id_item WHERE a.id_user = "' . $userId . '" AND a.status = 2 GROUP BY a.id DESC');

	$rowCancelled = $result_cancelled->fetchAll();


	$result_upcoming = $db->query('SELECT a.id as booking_id, a.id_hotel as hotel_id, a.nights, a.adults, a.children, FROM_UNIXTIME((a.from_date + 19800),"%Y-%m-%d") as checkedIn_date, FROM_UNIXTIME((a.to_date + 19800),"%Y-%m-%d") as checkedOut_date, a.amount as subtotal, a.discount as discount_amount, a.ex_tax as extax_amount, a.tax_amount as tax_amount, a.total as booking_amount, b.name as booking_location, c.title as hotel_name, d.label as tag, CONCAT("/medias/hotel/medium/", d.id,"/",d.file) as hotel_image, (SELECT COUNT(id_booking) FROM pm_booking_room WHERE id_booking = a.id) as rooms FROM pm_booking a INNER JOIN pm_destination b ON a.id_destination = b.id INNER JOIN pm_hotel c ON a.id_hotel = c.id LEFT JOIN pm_hotel_file d ON a.id_hotel = d.id_item WHERE a.id_user = "' . $userId . '" AND a.status <> 2 AND (a.checked_in != "in" OR a.checked_in is null) AND ((a.from_date+68399) > unix_timestamp()) GROUP BY a.id DESC');

	$rowUpcoming = $result_upcoming->fetchAll();


	$result_ongoing = $db->query('SELECT a.id as booking_id, a.id_hotel as hotel_id, a.nights, a.adults, a.children, FROM_UNIXTIME((a.from_date + 19800),"%Y-%m-%d") as checkedIn_date, FROM_UNIXTIME((a.to_date + 19800),"%Y-%m-%d") as checkedOut_date, a.amount as subtotal, a.discount as discount_amount, a.ex_tax as extax_amount, a.tax_amount as tax_amount, a.total as booking_amount, b.name as booking_location, c.title as hotel_name, d.label as tag, CONCAT("/medias/hotel/medium/", d.id,"/",d.file) as hotel_image, (SELECT COUNT(id_booking) FROM pm_booking_room WHERE id_booking = a.id) as rooms FROM pm_booking a INNER JOIN pm_destination b ON a.id_destination = b.id INNER JOIN pm_hotel c ON a.id_hotel = c.id LEFT JOIN pm_hotel_file d ON a.id_hotel = d.id_item WHERE a.id_user = "' . $userId . '" AND a.status <> 2 AND a.checked_in = "in" AND ((unix_timestamp() between (a.from_date+19800) and (a.to_date+19800)) OR ((a.from_date+19800) > unix_timestamp())) GROUP BY a.id DESC');

	$rowOngoing = $result_ongoing->fetchAll();

	$result['bookingCompleted'] = $rowCompleted;
	$result['bookingCancelled'] = $rowCancelled;
	$result['bookingUpcoming'] = $rowUpcoming;
	$result['bookingOngoing'] = $rowOngoing;


	$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array("bookingHistory" => $result));
	displayOutput($response);
} else {
	$response = array('status' => array('error_code' => 1, 'message' => 'Success'), 'response' => array("message" => "There is no Bookings Found!"));
	displayOutput($response);
}
