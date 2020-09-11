<?php

/**
 * Script called on Cancle Booking API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');


$data = json_decode(file_get_contents('php://input'), true);

$userId = $data['userId'];
$bookingId = $data['bookingId'];
$comment = $data['comment'];

if (empty($userId)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'User Id is required'));
	displayOutput($response);
}

if (empty($bookingId)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Booking Id is required'));
	displayOutput($response);
}

if (empty($comment)) {
	$response = array('status' => array('error_code' => 1, 'message' => 'Reason is required'));
	displayOutput($response);
}

$fromDAte = $db->query('SELECT FROM_UNIXTIME(from_date,"%Y-%m-%d") as checkin_date FROM pm_booking WHERE id = "' . $bookingId . '"');
$rowFrom = $fromDAte->fetch();

$toDAte = $db->query('SELECT FROM_UNIXTIME(to_date,"%Y-%m-%d") as checkout_date FROM pm_booking WHERE id = "' . $bookingId . '"');
$rowTo = $toDAte->fetch();

//$result_exists = $db->query('SELECT a.id_hotel as hotel_id, a.amount, DATEDIFF("' . $rowTo['checkout_date'] . '", "' . $rowFrom['checkin_date'] . '") AS days, b.duration_type, b.value, b.fees, b.fees_type FROM pm_booking a INNER JOIN pm_hotel_cancel_policy b ON a.id_hotel = b.id_hotel WHERE a.id = "' . $bookingId . '"');
$result_exists = $db->query('SELECT a.id_hotel as hotel_id, a.amount, DATEDIFF("' . $rowTo['checkout_date'] . '", "' . $rowFrom['checkin_date'] . '") AS days, b.duration_type, b.value, b.fees, b.fees_type FROM pm_booking a LEFT JOIN pm_hotel_cancel_policy b ON a.id_hotel = b.id_hotel WHERE a.id = "' . $bookingId . '"');
$booking = $db->query("SELECT * FROM pm_booking AS pbm WHERE pbm.`id` = " . $bookingId)->fetch();


if ($booking['status'] == 4 && $booking['payment_option'] == 'arrival') {
	$response = array('status' => array('error_code' => 1, 'message' => 'Can not be cancelled'));
	displayOutput($response);
}
if ($booking['checked_in'] == 'in') {
	$response = array('status' => array('error_code' => 1, 'message' => 'You are Checked In. Can not be cancelled'));
	displayOutput($response);
}
if ($result_exists !== false && $db->last_row_count() > 0) {
	$rowPolicy = $result_exists->fetchAll();
	$checkin = $booking['from_date'];
	$cin = date("d/m/Y", $checkin);
	$cancelpolicy = array();
	$conditions = [];
	$newArray = [];
	foreach ($rowPolicy as $key => $val) {
		$duration = 0;
		if ($val['duration_type'] == 'day') {
			$duration = $val['value'] * 24;
			$newArray[$duration][] = $val;
		} else {
			$duration = $val['value'];
			$newArray[$duration][] = $val;
		}
	}
	foreach ($newArray as $key => $val) {
		if ($key == NULL || $key == '') {
			$conditions = $val;
		}
	}
	ksort($conditions);
	foreach ($conditions as $key => $condition) {
		$bfore = '- ' . $key . ' hours';
		$checkin_before = strtotime($bfore, $checkin);
		$today = time() + 19800;
		if (($today >= $checkin_before) && ($today <= $checkin)) {
			$cancelpolicy = $condition;
			break;
		}
	}
	if (!empty($cancelpolicy)) {
		$total_amount = $cancelpolicy[0]['amount'];
		$cancel_fees = $cancelpolicy[0]['fees'];
		$fees_type = $cancelpolicy[0]['fees_type'];
		$booking_days = $cancelpolicy[0]['days'];
	} else {
		$total_amount = $conditions[0]['amount'];
		$cancel_fees = $conditions[0]['fees'];
		$fees_type = $conditions[0]['fees_type'];
		$booking_days = $conditions[0]['days'];
	}
	if ($booking['payment_option'] != 'arrival') {
		if ($fees_type == 'parcentage') {
			$refundCharge = ($total_amount * $cancel_fees) / 100;
			$refundAmount = $total_amount - $refundCharge;
		} else {
			$refundCharge = $cancel_fees;
			$refundAmount = $total_amount - $refundCharge;
		}
	} else {
		$refundCharge = 0.00;
		$refundAmount = 0.00;
	}
	$getRooms = $db->query("SELECT GROUP_CONCAT(id) as room_booking_id, id_room FROM pm_booking_room AS pbm WHERE pbm.`id_booking` = " . $bookingId)->fetch();
	$sqlCancle = $db->query('INSERT INTO pm_booking_cancel (id_booking, id_room, cancel_type, reason, days, rooms, booking_amount, refund_charge, refund_amount) VALUES ("' . $bookingId . '", "' . $getRooms['id_room'] . '", "full","' . $comment . '", "' . $booking_days . '", "' . $getRooms['room_booking_id'] . '", "' . $total_amount . '", "' . $refundCharge . '", "' . $refundAmount . '")');
	if ($sqlCancle) {
		$cancleUpdate = $db->query('UPDATE pm_booking SET status = 2 WHERE id = "' . $bookingId . '"');
		//Push Notification FOR Android
		$tokenSql = $db->query('SELECT * FROM pm_device WHERE user_id  = "' . $userId . '" AND device_type = 1');
		$rowToken = $tokenSql->fetch();
		$fcm_token = $rowToken['fcm_token'];
		$deviece_type = $rowToken['device_type'];
		$title = 'Booking Cancel';
		$body = 'Booking is Successfully Cancelled. Booking ID :' . $bookingId;
		push_notify_send($fcm_token, $deviece_type, $title, $body);
		//Push Notification FOR Android
		//Notification List					
		$noti = array();
		$noti['id'] = null;
		$noti['user_id'] = $userId;
		$noti['action_id'] = $bookingId;
		$noti['notification_title'] = 'Booking Cancel';
		$noti['notification_desc'] = 'Booking is Successfully Cancelled. Booking ID :' . $bookingId;
		$noti['action_url'] = null;
		$noti['status'] = '0';
		$noti['created_ts'] = date('Y-m-d h:i:s');
		$insertNoti = db_prepareInsert($db, 'pm_notification', $noti);
		$insertNoti->execute();
		//Notification List
		//Cancel Email
		$userDetailsQuery = 'SELECT * FROM pm_user WHERE id = ' . $userId;
		$userDetails = $db->query($userDetailsQuery)->fetch(PDO::FETCH_ASSOC);
		$subject = 'Your Booking is Cancelled';
		$subject1 = 'Booking is Cancelled';
		$content1 = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;"><p style="margin: 20px 0 20px;">Your booking ' . $bookingId . ' at HMS for check-in date: ' . gmstrftime(DATE_FORMAT, $booking['from_date']) . ' has been cancelled.</p>';
		$content1 .= '<p style="text-transform: capitalize; ">Cancel  Mode : <strong> Full </strong></p>';
		$content1 .= '<p>Cancel Reason : <strong>' . $comment . '</strong></p>';
		$content1 .= '<p>Cancellation   Charge :<strong>' . formatPrice($refundCharge * CURRENCY_RATE) . '</strong> </p>';
		$content1 .= '<p>Refund amount : <strong>' . formatPrice($refundAmount * CURRENCY_RATE) . '</strong></p>';
		$content1 .= '<p>Cancelled Date : <strong>' . date('D, jS F Y') . '</strong></p>';
		$content1 .= '<p style="margin: 0 0 20px;">We hope to host you in the future.</p>';
		$content1 .= '<p style="margin: 0 0 20px;">Cheers <br>HMS</p></td></tr>';
		$content = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;"><p style="margin: 20px 0 20px;">Your booking ' . $bookingId . ' at HMS for check-in date: ' . gmstrftime(DATE_FORMAT, $booking['from_date']) . ' has been cancelled.</p>';
		$content .= '<p style="text-transform: capitalize; ">Cancel  Mode : <strong> Full </strong></p>';
		$content .= '<p>Cancel Reason : <strong>' . $comment . '</strong></p>';
		$content .= '<p>Cancellation   Charge :<strong>' . formatPrice($refundCharge * CURRENCY_RATE) . '</strong> </p>';
		$content .= '<p>Refund amount : <strong>' . formatPrice($refundAmount * CURRENCY_RATE) . '</strong></p>';
		$content .= '<p>Cancelled Date : <strong>' . date('D, jS F Y') . '</strong></p>';
		$content .= '<p style="margin: 0 0 20px;">We hope to host you in the future.</p>';
		$content .= '<p style="margin: 0 0 20px;">Cheers <br>HMS</p></td></tr>';
		$from_email = 'cancellation@hms.com';
		$from_name = 'HMS';
		sendMail(EMAIL, OWNER, $subject1, $content1, $userDetails['email'], $userDetails['firstname'] . ' ' . $userDetails['lastname'], $from_email, $from_name);
		sendMail($userDetails['email'], $userDetails['firstname'] . ' ' . $userDetails['lastname'], $subject, $content, '', '', $from_email, $from_name);
		$mobile = $userDetails['mobile'];
		$message = 'Hi  Your booking # ' . $bookingId . ' has been successfully cancelled';
		httpPost($mobile, $message);
		// Add activity log
		$logPurpose = 'Booking Id ' . $bookingId . ' Cancelled on ' . date('d-m-Y') . '. Reason : ' . $comment . ' - App mode';
		add_activity_log($userId, $bookingId, 'booking', 'cancel', $logPurpose);
		$response = array('status' => array('error_code' => 0, 'message' => 'Success'));
		displayOutput($response);
	}
} else {
	$response = array('status' => array('error_code' => 1, 'message' => 'There is no Cancel Policy Found!'));
	displayOutput($response);
}
