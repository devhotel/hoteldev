<?php

/**
 * Script called on Confirm Booking API
 */
require_once('../../common/lib.php');
require_once('../../common/define.php');
$data = json_decode(file_get_contents('php://input'), true);
$hotelId = $data['hotelId'];
$payUResponse = array();
if (array_key_exists("payUResponse", $data)) {
	$payUResponse = $data['payUResponse'];
}

//print_r($payUResponse);
//die;
$downPayment = 0.00;
if (!empty($payUResponse)) {
	$downPayment = $payUResponse['result']['amount'];
}
//$fromDate = $data['fromDate'];
$fromDate = date_format(date_create($data['fromDate']), 'Y-m-d H:i:s');
//$toDate = $data['toDate'];
$toDate = date_format(date_create($data['toDate']), 'Y-m-d H:i:s');
// echo $fromDate . '  ' . $toDate;
// die;
$couponUsed = (isset($data['coupon'])) ? $data['coupon'] : '';
$cartTotal = $data['cartTotal'];
$taxTotal = $data['taxTotal'];
$coupondiscountAmount = $data['coupondiscountAmount'];
$rooms = $data['rooms'];
$bookedRoom = '';
foreach ($rooms as $room) {
	if ($bookedRoom == '') {
		$bookedRoom = $room['name'];
	} else {
		$bookedRoom .= ', ' . $room['name'];
	}
}
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

// if (empty($userId)) {
// 	$response = array('status' => array('error_code' => 1, 'message' => 'User Id is required'));
// 	displayOutput($response);
// }

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

$userId = '';
$userMessage = '';
if (isset($data['userId'])) {
	$userId = $data['userId'];
	$userMessage = 'existingUser';
}
if ($userId == '') {
	$checkUserEmail = $db->query("SELECT id FROM pm_user WHERE email = '" . $guestDetails['email'] . "'")->fetch(PDO::FETCH_ASSOC);
	if (!empty($checkUserEmail)) {
		$userId = $checkUserEmail['id'];
		$userMessage = 'existingUser';
	} else {
		$checkUserPhone = $db->query("SELECT id FROM pm_user WHERE mobile = '" . $guestDetails['pnoneNo'] . "'")->fetch(PDO::FETCH_ASSOC);
		if (!empty($checkUserPhone)) {
			$userId = $checkUserPhone['id'];
			$userMessage = 'existingUser';
		} else {
			$newUserQuery = "INSERT INTO `pm_user`(`firstname`, `lastname`, `email`, `login`, `pass`, `type`, 
			`add_date`, `checked`, `address`, `country`, `postcode`, `city`, `country_code`, `mobile`) VALUES (
				'" . $guestDetails['firstName'] . "', '" . $guestDetails['lastName'] . "', '" . $guestDetails['email'] . "',
				'" . $guestDetails['email'] . "', '" . MD5('123456') . "', 'registered', '" . time() . "', '1',
				'" . $guestDetails['address'] . "', '" . $guestDetails['country'] . "', '" . $guestDetails['postCode'] . "',
				'" . $guestDetails['city'] . "', '91', '" . $guestDetails['pnoneNo'] . "')";
			$newUser = $db->prepare($newUserQuery);
			$newUser->execute();
			$userId = $db->lastInsertId();
			$userMessage = 'newUser';
		}
	}
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
$data['coupon_used'] = $couponUsed;
if (!empty($payUResponse)) {
	$data['status'] = 4;
	$data['down_payment'] = $cartTotal;
	$data['trans'] = $payUResponse['result']['payuMoneyId'];
} else {
	$data['status'] = 1;
}
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
		//$bookingPayment['trans'] = $payment['paymentMode'];
		$bookingPayment['trans'] = (!empty($payUResponse)) ? (string) $payUResponse['result']['payuMoneyId'] : $payment['paymentMode'];
		$bookingPayment['payu_response'] = json_encode($payUResponse);
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
			//// USER EMAIL START
			$room_content = '';
			$room_content .= '<table cellpadding="0" cellspacing="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;">';
			$room_content .= '<tbody>';
			$room_content .= '<tr>';
			$room_content .= '<td style="padding: 20px;background: #fff;">';
			$room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">No. of Guests</h2>';
			$room_content .= '</td>';
			$room_content .= '<td style="padding: 20px;background: #fff;text-align: center;">';
			$room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">No. of Rooms</h2>';
			$room_content .= '</td>';
			$room_content .= '<td style="padding: 20px;background: #fff;text-align: right;">';
			$room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">Room Type</h2>';
			$room_content .= '</td>';
			$room_content .= '</tr>';
			$totAdult = 0;
			$totKid = 0;
			$roomType = '';
			foreach ($rooms as $val) {
				if ($roomType == '') {
					$roomType = $val['name'];
				} else {
					$roomType = ', ' . $val['name'];
				}
				$room_content .= '<tr>';
				$adult = 0;
				$kid = 0;
				$key = 0;
				$row_content = '';
				$adult += $val['noOfAdult'];
				$kid += $val['noOfChild'];
				$room_content .= '<td style="padding: 20px;background: #fff;">';
				$room_content .= '<p style="font-size: 16px;color: #000;margin: 0;">';
				$room_content .=  $adult;
				$room_content .= ' Adult,';
				$room_content .= $kid;
				$room_content .= ' Kid';
				$room_content .= '</p>';
				$room_content .= '</td>';
				$room_content .= '<td style="padding: 20px;background: #fff;text-align: center;">';
				$room_content .= '<p style="font-size: 16px;color: #000;margin: 0;">' . $val['roomQty'] . '</p>';
				$room_content .= '</td>';
				$room_content .= '<td style="padding: 20px;background: #fff;text-align: right;">';
				$room_content .= '<p style="font-size: 16px;color: #000;margin: 0;">' . $rowHotel['title'] . ' - ' . $val['name'] . '</p>';
				$room_content .= '</td>';
				$room_content .= '</tr>';
				$totAdult += $adult;
				$totKid += $kid;
			}
			$room_content .= '</tbody>';
			$room_content .= '</table>';
			$users = '';
			$result_owner = $db->query('SELECT users FROM pm_hotel WHERE id = ' . $data['id_hotel']);
			if ($result_owner !== false && $db->last_row_count() > 0) {
				$row = $result_owner->fetch();
				$users = $row['users'];
			}
			$hotel_owners = array();
			$result_owner = $db->query('SELECT * FROM pm_user WHERE id IN (' . $users . ')');
			if ($result_owner !== false && $db->last_row_count() > 0) {
				$hotel_owners = $result_owner->fetchAll();
			}
			$service_content = '';
			$activity_content = '';
			$tax_content = '';
			/*$service_content = '';
			if (isset($_SESSION['book']['extra_services']) && count($_SESSION['book']['extra_services']) > 0) {
				foreach ($_SESSION['book']['extra_services'] as $id_service => $service)
					$service_content .= $service['title'] . ' x ' . $service['qty'] . ' : ' . formatPrice($service['amount'] * CURRENCY_RATE) . ' ' . $texts['INCL_VAT'] . '<br>';
			}

			$activity_content = '';
			if (isset($_SESSION['book']['activities']) && count($_SESSION['book']['activities']) > 0) {
				foreach ($_SESSION['book']['activities'] as $id_activity => $activity) {
					$activity_content .= '<p><b>' . $activity['title'] . '</b> - ' . $activity['duration'] . ' - ' . gmstrftime(DATE_FORMAT . ' ' . TIME_FORMAT, $activity['session_date']) . '<br>
					' . ($activity['adults'] + $activity['children']) . ' ' . $texts['PERSONS'] . ' - 
					' . $texts['ADULTS'] . ': ' . $activity['adults'] . ' / 
					' . $texts['CHILDREN'] . ': ' . $activity['children'] . '<br>
					' . $texts['PRICE'] . ' : ' . formatPrice($activity['amount'] * CURRENCY_RATE) . '</p>';
				}
			}
			if (isset($_SESSION['book']['taxslab'])) {
				$tax_content = '';
				$tax_content .= db_getFieldValue($db, 'pm_tax', 'name', $_SESSION['book']['stax_id'], $lang = 0) . ': ' . formatPrice($_SESSION['book']['slab_tax_amount'] * CURRENCY_RATE) . '<br>';
			} else {
				$tax_id = 0;
				$tax_content = '';
				$result_tax = $db->prepare('SELECT * FROM pm_tax WHERE id = :tax_id AND checked = 1 AND value > 0 AND lang = ' . LANG_ID . ' ORDER BY rank');
				$result_tax->bindParam(':tax_id', $tax_id);
				foreach ($_SESSION['book']['taxes'] as $tax_id => $taxes) {
					$tax_amount = 0;
					foreach ($taxes as $amount) $tax_amount += $amount;
					if ($tax_amount > 0) {
						if ($result_tax->execute() !== false && $db->last_row_count() > 0) {
							$row = $result_tax->fetch();
							$tax_content .= $row['name'] . ': ' . formatPrice($tax_amount * CURRENCY_RATE) . '<br>';
						}
					}
				}
			}*/
			$payment_notice = '';
			if ($payment['paymentOption'] == 'online') $payment_notice .= str_replace('{amount}', '<b>' . formatPrice($downPayment * CURRENCY_RATE) . ' ' . $texts['INCL_VAT'] . '</b>', $texts['PAYMENT_CHECK_NOTICE']);
			if ($payment['paymentOption'] == 'wallet') $payment_notice .= str_replace('{amount}', '<b>' . formatPrice($downPayment * CURRENCY_RATE) . ' ' . $texts['INCL_VAT'] . '</b>', $texts['PAYMENT_CHECK_NOTICE']);
			if ($payment['paymentOption'] == 'arrival') $payment_notice .= str_replace('{amount}', '<b>' . formatPrice($downPayment) . ' ' . $texts['INCL_VAT'] . '</b>', $texts['PAYMENT_ARRIVAL_NOTICE']);
			$userDetailsQuery = 'SELECT * FROM pm_user WHERE id = ' . $data['id_user'];
			$userDetails = $db->query($userDetailsQuery)->fetch(PDO::FETCH_ASSOC);
			if (empty($payUResponse)) {
				$mail = getMail($db, 'BOOKING_CONFIRMATION', array(
					'{booking_id}' 		=> $rowDetails['booking_id'],
					'{booking_date}'	=> gmstrftime(DATE_FORMAT, $data['payment_date']),
					'{firstname}' 		=> $userDetails['firstname'],
					'{lastname}' 		=> $userDetails['firstname'],
					'{company}' 		=> $userDetails['company'],
					'{address}' 		=> $userDetails['address'],
					'{postcode}' 		=> $userDetails['postcode'],
					'{city}' 			=> $userDetails['city'],
					'{country}' 		=> $userDetails['country'],
					'{phone}' 			=> $userDetails['phone'],
					'{mobile}' 			=> $userDetails['mobile'],
					'{email}' 			=> $userDetails['email'],
					'{Check_in}' 		=> gmstrftime(DATE_FORMAT, $data['from_date']),
					'{Check_out}' 		=> gmstrftime(DATE_FORMAT, $data['to_date']),
					'{num_nights}' 		=> $data['nights'],
					'{num_guests}' 		=> ($totAdult + $totKid),
					'{num_adults}' 		=> $totAdult,
					'{num_children}' 	=> $totKid,
					'{rooms}' 			=> $room_content,
					'{room_type}' 		=> $roomType,
					'{extra_services}' 	=> $service_content,
					'{activities}' 		=> $activity_content,
					'{comments}' 		=> '',
					'{total_cost}' 		=> formatPrice(($cartTotal - $taxTotal) * CURRENCY_RATE),
					'{tourist_tax}' 	=> formatPrice($taxTotal * CURRENCY_RATE),
					'{discount}' 		=> '- ' . formatPrice($coupondiscountAmount * CURRENCY_RATE),
					'{taxes}' 			=> $tax_content,
					'{down_payment}' 	=> formatPrice($downPayment * CURRENCY_RATE),
					'{total}' 			=> formatPrice($cartTotal * CURRENCY_RATE),
					'{payment_notice}' 	=> $payment_notice
				));
			}else{
				$mail = getMail($db, 'BOOKING_CONFIRMATION_ONLINE', array(
					'{booking_id}' 		=> $rowDetails['booking_id'],
					'{booking_date}'	=> gmstrftime(DATE_FORMAT, $data['payment_date']),
					'{firstname}' 		=> $userDetails['firstname'],
					'{lastname}' 		=> $userDetails['firstname'],
					'{company}' 		=> $userDetails['company'],
					'{address}' 		=> $userDetails['address'],
					'{postcode}' 		=> $userDetails['postcode'],
					'{city}' 			=> $userDetails['city'],
					'{country}' 		=> $userDetails['country'],
					'{phone}' 			=> $userDetails['phone'],
					'{mobile}' 			=> $userDetails['mobile'],
					'{email}' 			=> $userDetails['email'],
					'{Check_in}' 		=> gmstrftime(DATE_FORMAT, $data['from_date']),
					'{Check_out}' 		=> gmstrftime(DATE_FORMAT, $data['to_date']),
					'{num_nights}' 		=> $data['nights'],
					'{num_guests}' 		=> ($totAdult + $totKid),
					'{num_adults}' 		=> $totAdult,
					'{num_children}' 	=> $totKid,
					'{rooms}' 			=> $room_content,
					'{room_type}' 		=> $roomType,
					'{extra_services}' 	=> $service_content,
					'{activities}' 		=> $activity_content,
					'{comments}' 		=> '',
					'{total_cost}' 		=> formatPrice(($cartTotal - $taxTotal) * CURRENCY_RATE),
					'{tourist_tax}' 	=> formatPrice($taxTotal * CURRENCY_RATE),
					'{discount}' 		=> '- ' . formatPrice($coupondiscountAmount * CURRENCY_RATE),
					'{taxes}' 			=> $tax_content,
					'{down_payment}' 	=> formatPrice($downPayment * CURRENCY_RATE),
					'{total}' 			=> formatPrice($cartTotal * CURRENCY_RATE),
					'{payment_notice}' 	=> $payment_notice
				));
			}
			$from_email = 'bookings@hms.com';
			$from_name = 'HMS';
			$from_subject = 'New Booking Received';
			if ($mail !== false) {
				foreach ($hotel_owners as $owner) {
					if ($owner['email'] != EMAIL)
						sendMail($owner['email'], $owner['firstname'], $from_subject, $mail['content'], $userDetails['email'], $userDetails['firstname'] . ' ' . $userDetails['lastname'], $from_email, $from_name);
				}
				sendMail(EMAIL, OWNER, $from_subject, $mail['content'], $userDetails['email'], $userDetails['firstname'] . ' ' . $userDetails['lastname'], $from_email, $from_name);
				sendMail($userDetails['email'], $userDetails['firstname'] . ' ' . $userDetails['lastname'], $mail['subject'], $mail['content'], '', '', $from_email, $from_name);
			}
			//// USER EMAIL END
			$mobile =  $data['mobile'];
			$message = 'Hi ' . $data['firstname'] . ' Your booking # ' . $rowDetails['booking_id'] . ' is successfully Confirmed';
			httpPost($mobile, $message);
			$adminMobile = explode(' + ', PHONE);
			$adminMessage = "Booking Received -\r\nHOTEL NAME - " . $rowHotel['title'] . "\r\nBOOKING ID - " . $rowDetails['booking_id'] . "\r\nGUEST NAME - " . $data['firstname'] . " " . $data['lastname'] . "\r\nCHECK IN DATE - " . gmstrftime(DATE_FORMAT, $data['from_date']) . "\r\nCHECK OUT DATE - " . gmstrftime(DATE_FORMAT, $data['to_date']) . "\r\nROOM - " . $bookedRoom . "\r\nAMOUNT - " . formatPrice($cartTotal * CURRENCY_RATE) . "\r\nPAYMENT AT - " .  $payment['paymentMode'] . "\r\nBOOKING SOURCE - APP\r\nGUEST MOBILE NO - " . $data['mobile'];
			httpPost($adminMobile[1], $adminMessage);
			//Notification List
			$logPurpose = 'Booking Id ' . $bookingId . ' created on ' . date('d-m-Y') . ' - App mode';
			// Add activity log
			add_activity_log($userId, $bookingId, 'booking', 'create', $logPurpose);
			$result['userMessage'] = $userMessage;
			$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array('booking_details' => $result));
			displayOutput($response);
		}
	}
} else {
	$response = array('status' => array('error_code' => 1, 'message' => 'Failure'));
	displayOutput($response);
}
