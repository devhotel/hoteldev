<?php

/**
 * Script called on Confirm Booking API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');
$data = json_decode(file_get_contents('php://input'), true);
$hotelId = $data['hotelId'];
$userId = $data['userId'];
//$fromDate = $data['fromDate'];
$fromDate = date_format(date_create($data['fromDate']), 'Y-m-d H:i:s');
//$toDate = $data['toDate'];
$toDate = date_format(date_create($data['toDate']), 'Y-m-d H:i:s');

// echo $fromDate . '  ' . $toDate;
// die;


$cartTotal = $data['cartTotal'];
$taxTotal = $data['taxTotal'];
$coupondiscountAmount = $data['coupondiscountAmount'];
$rooms = $data['rooms'];
$guestDetails = $data['guestDetails'];
$payment = $data['payment'];


if (empty($coupondiscountAmount)) {
	$subTotal = $cartTotal - $taxTotal;
} else {
	$subTotal = ($cartTotal - $taxTotal) + $coupondiscountAmount;
}

if (empty($hotelId)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Hotel Id is required'));
	displayOutput($response);
}

if (empty($userId)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'User Id is required'));
	displayOutput($response);
}

if (empty($fromDate)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Check In Date is required'));
	displayOutput($response);
}
if (empty($toDate)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Check Out Date is required'));
	displayOutput($response);
}

if (empty($cartTotal)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Total Amount is required'));
	displayOutput($response);
}

if (empty($taxTotal)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Tax Amount is required'));
	displayOutput($response);
}

if (in_array('', array_column($rooms, 'roomId'))) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Room Id is required in Rooms'));
	displayOutput($response);
}

if (in_array('', array_column($rooms, 'roomPrice'))) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Room Price is required in Rooms'));
	displayOutput($response);
}

if (in_array('', array_column($rooms, 'noOfAdult'))) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Number of Adults is required in Rooms'));
	displayOutput($response);
}

if (in_array('', array_column($rooms, 'noOfChild'))) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Number of Child is required in Rooms'));
	displayOutput($response);
}

if (empty($guestDetails['firstName'])) {
	$response = array('status' => array('error_code' => 1, 'message' => 'First Name is required'));
	displayOutput($response);
}

if (empty($guestDetails['lastName'])) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Last Name is required'));
	displayOutput($response);
}

if (empty($guestDetails['email'])) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Email Id is required'));
	displayOutput($response);
}

if (empty($guestDetails['pnoneNo'])) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Phone Number is required'));
	displayOutput($response);
}

/*if(empty($guestDetails['address'])){
  $response=array('status'=>array('error_code'=>1,'message'=>'Address is required'));
  displayOutput($response);
}

if(empty($guestDetails['postCode'])){
  $response=array('status'=>array('error_code'=>1,'message'=>'Postcode is required'));
  displayOutput($response);
}

if(empty($guestDetails['country'])){
  $response=array('status'=>array('error_code'=>1,'message'=>'Country is required'));
  displayOutput($response);
}

if(empty($guestDetails['city'])){
  $response=array('status'=>array('error_code'=>1,'message'=>'Tax Amount is required'));
  displayOutput($response);
}*/

$result_user = $db->query('SELECT govid_type, govid, company FROM pm_user WHERE id = "' . $userId . '"');
$rowUser = $result_user->fetch();

$result_hotel = $db->query('SELECT title, users, id_destination, id_accommodation FROM pm_hotel WHERE id = "' . $hotelId . '"');
$rowHotel = $result_hotel->fetch();

$result_accoTax = $db->query('SELECT name as taxName, value as taxValue FROM pm_tax WHERE FIND_IN_SET("' . $rowHotel['id_accommodation'] . '",id_accommodation) > 0');
$rowAccotax = $result_accoTax->fetch();

$taxName = $rowAccotax['taxName'];
$taxValue = $rowAccotax['taxValue'];

$date1 = new DateTime($fromDate);
$date2 = new DateTime($toDate);
// this calculates the diff between two dates, which is the number of nights
$numberOfNights = $date2->diff($date1)->format("%a");

$totalAdult = 0;
$totalChild = 0;
foreach ($rooms as $key => $value) {
	$totalAdult += $value['noOfAdult'];
	$totalChild += $value['noOfChild'];
}
// $tmp = date_format(date_create($fromDate), 'Y-m-d H:i:s');
// $timestamp = strtotime($tmp);
// echo $tmp . ' ' . $timestamp . ' ' . date("Y-m-d H:i:s", $timestamp);
// die;
$bookingId = 0;
$data = array();
$data['id_destination'] = $rowHotel['id_destination'];
$data['id_hotel'] = $hotelId;
$data['add_date'] = time();
$data['from_date'] = strtotime($fromDate) + 19800;
$data['to_date'] = strtotime($toDate) + 19800;
$data['nights'] = $numberOfNights;
$data['adults'] = $totalAdult;
$data['children'] = $totalChild;
$data['amount'] = $subTotal;
$data['discount'] = $coupondiscountAmount;
$data['ex_tax'] = $cartTotal - $taxTotal;
$data['tax_amount'] = $taxTotal;
$data['total'] = $cartTotal;
$data['id_user'] = $userId;
$data['firstname'] = $guestDetails['firstName'];
$data['lastname'] = $guestDetails['lastName'];
$data['email'] = $guestDetails['email'];
$data['company'] = $rowUser['govid_type'];
$data['govid_type'] = $rowUser['govid_type'];
$data['govid'] = $rowUser['govid'];
$data['address'] = $guestDetails['address'];
$data['postcode'] = $guestDetails['postCode'];
$data['city'] = $guestDetails['city'];
$data['mobile'] = $guestDetails['pnoneNo'];
$data['country'] = $guestDetails['country'];
$data['payment_date'] = time();
$data['payment_option'] = $payment['paymentOption'];
$data['payment_mode'] = $payment['paymentMode'];
$data['users'] = $rowHotel['users'];
$data['source'] = 'app';
$data['status'] = 1;

$result_booking = db_prepareInsert($db, 'pm_booking', $data); //Create Booking

if ($result_booking->execute() !== false) {

	$bookingId = $db->lastInsertId();

	foreach ($rooms as $key => $value) {

		$sql = $db->query('SELECT CONCAT("' . $rowHotel['title'] . '","-",title) as room_name FROM pm_room WHERE id = "' . $value['roomId'] . '"');
		$rowsql = $sql->fetch();

		$result_room_booking = $db->query('INSERT INTO pm_booking_room (id_booking, id_room, id_hotel, title, children, adults, amount, ex_tax, chk) VALUES ("' . $bookingId . '", "' . $value['roomId'] . '", "' . $hotelId . '", "' . $rowsql['room_name'] . '", "' . $value['noOfChild'] . '", "' . $value['noOfAdult'] . '", "' . $value['roomPrice'] . '", "' . $value['roomPrice'] . '", 1)'); //Create Room Booking

	}

	$bookingTax = array();
	$bookingTax['id_booking'] = $bookingId;
	$bookingTax['name'] = $taxName;
	$bookingTax['amount'] = $taxTotal;

	$result_tax = db_prepareInsert($db, 'pm_booking_tax', $bookingTax); //Create Booking Tax

	if ($result_tax->execute() !== false) {

		$bookingPayment = array();
		$bookingPayment['id_booking'] = $bookingId;
		$bookingPayment['descr'] = $payment['paymentDesc'];
		$bookingPayment['method'] = $payment['paymentMode'];
		$bookingPayment['amount'] = $cartTotal;
		$bookingPayment['trans'] = $payment['paymentMode'];
		$bookingPayment['date'] = time();

		$result_payment = db_prepareInsert($db, 'pm_booking_payment', $bookingPayment); //Create Booking Payment

		if ($result_payment->execute() !== false) {

			$fromDAte = $db->query('SELECT FROM_UNIXTIME(from_date,"%Y-%m-%d") as checkin_date FROM pm_booking WHERE id = "' . $bookingId . '"');
			$rowFrom = $fromDAte->fetch();

			$toDAte = $db->query('SELECT FROM_UNIXTIME(to_date,"%Y-%m-%d") as checkout_date FROM pm_booking WHERE id = "' . $bookingId . '"');
			$rowTo = $toDAte->fetch();



			$result_details = $db->query('SELECT a.id as booking_id, CONCAT(a.address, ", ", a.city," ",a.postcode) as booking_address, CONCAT(a.firstname, " ",a.lastname) as booking_name, FROM_UNIXTIME((a.from_date+86400),"%Y-%m-%d") as checkin_date, FROM_UNIXTIME((a.to_date+86400),"%Y-%m-%d") as checkout_date, DATEDIFF("' . $rowTo['checkout_date'] . '", "' . $rowFrom['checkin_date'] . '") AS days, b.id as hotel_id, b.title as name, b.phone, b.lat as latitude, b.lng as longitude, c.label as tag, CONCAT("/medias/hotel/medium/", c.id,"/",c.file) as hotel_image, b.city as hotel_address FROM pm_booking a INNER JOIN pm_hotel b ON a.id_hotel = b.id LEFT JOIN pm_hotel_file c ON a.id_hotel = c.id_item WHERE a.id = "' . $bookingId . '"');
			$rowDetails = $result_details->fetch();

			$result_room = $db->query('SELECT a.id_room as room_id, COUNT(a.id) as room_qty, SUM(a.adults) as noOfAdult, SUM(a.children) as noOfChild, SUM(a.amount) as total_amount, b.title as name FROM pm_booking_room a INNER JOIN pm_room b ON a.id_room = b.id WHERE a.id_booking = "' . $bookingId . '" GROUP BY a.id_room ');
			$rowRooms = $result_room->fetchAll();

			$result = array();
			$result = $rowDetails;
			$result['rooms'] = $rowRooms;

			//Push Notification FOR Android
			$tokenSql = $db->query('SELECT * FROM pm_device WHERE user_id  = "' . $userId . '" AND device_type = 1');
			$rowToken = $tokenSql->fetch();

			$fcm_token = $rowToken['fcm_token'];
			$deviece_type = $rowToken['device_type'];
			$title = 'Booking Confirm';
			$body = 'Booking is Successfully Done. Booking ID :' . $rowDetails['booking_id'];

			push_notify_send($fcm_token, $deviece_type, $title, $body);
			//Push Notification FOR Android

			//Notification List					
			$noti = array();
			$noti['id'] = null;
			$noti['user_id'] = $userId;
			$noti['action_id'] = $rowDetails['booking_id'];
			$noti['notification_title'] = 'Booking Confirm';
			$noti['notification_desc'] = 'Booking is Successfully Done. Booking ID :' . $rowDetails['booking_id'];
			$noti['action_url'] = null;
			$noti['status'] = '0';
			$noti['created_ts'] = date('Y-m-d h:i:s');

			$insertNoti = db_prepareInsert($db, 'pm_notification', $noti);
			$insertNoti->execute();
			//Notification List

			$logPurpose = 'Booking Id ' . $bookingId . ' created on ' . date('d-m-Y') . ' - app mode';
			// Add activity log
			add_activity_log($userId, $bookingId, 'booking', 'create', $logPurpose);

			$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array('booking_details' => $result));
			displayOutput($response);
		}
	}
} else {

	$response = array('status' => array('error_code' => 1, 'message' => 'Failure'));
	displayOutput($response);
}
