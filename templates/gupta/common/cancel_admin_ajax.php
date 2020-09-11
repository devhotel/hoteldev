<?php
require_once('../../../common/lib.php');
require_once('../../../common/define.php');
require_once('functions.php');
if (isset($_POST['booking_id']) && isset($_SESSION['admin']['id']) && isset($_POST['reason'])) {
	$booking_detail = $db->query("SELECT * FROM pm_booking_room AS pbm WHERE pbm.`id_booking` = " . $_POST['booking_id'])->fetch();
	$room_detail = $db->query("SELECT * FROM pm_room AS pr WHERE pr.`id` = " . $booking_detail['id_room'])->fetch();
	//print_r($room_detail);

	$reason = $_POST['reason'];
	$id_booking = $booking_detail['id_booking'];
	$amount = $db->query("SELECT * FROM pm_booking WHERE id = " . $id_booking)->fetch()['total'];
	$id_room = $booking_detail['id_room'];

	$number_of_days_full = $room_detail['number_of_days_full'];
	$number_of_days_cancel = $room_detail['number_of_days_cancel'];
	$cancel_fees = $room_detail['cancel_fees'];
	$fees_type = $room_detail['fees_type'];
	$result_booking = $db->query('SELECT * FROM pm_booking WHERE id = ' . $id_booking)->fetch();
	$from_date = $result_booking['from_date'];
	$status = $result_booking['status'];
	$now = time();
	// if ($result_booking['payment_option'] != 'arrival') {
	// 	$datediff = $from_date - $now;
	// 	$left_days = round($datediff / (60 * 60 * 24));
	// 	if ($left_days > $number_of_days_full) {
	// 		$refund_charge = 0.00;
	// 		$refund_amount = $amount;
	// 	} else {
	// 		if ($fees_type == 'parcentage') {
	// 			$refund_charge = (($cancel_fees * $amount) / 100);
	// 			$refund_amount = $amount - $refund_charge;
	// 		} else {
	// 			$refund_charge = $cancel_fees;
	// 			$refund_amount = $amount - $refund_charge;
	// 		}
	// 	}
	// } else {
	// 	$refund_charge = $amount;
	// 	$refund_amount = 0.00;
	// }
	/* New Cancel Amount Checking Start*/
	$bookingDates = $fromDAte = $db->query('SELECT payment_option, total, FROM_UNIXTIME(from_date,"%Y-%m-%d") as checkin_date, FROM_UNIXTIME(to_date,"%Y-%m-%d") as checkout_date FROM pm_booking WHERE id = "' . $id_booking . '"')->fetch(PDO::FETCH_ASSOC);
	$cancelDateDiff = date_diff(date_create($bookingDates['checkin_date'] . ' 12:00:00'), date_create(date('Y-m-d H:i:s')));
	$cancelDateDifferenceHours = ($cancelDateDiff->format("%a") * 24) + $cancelDateDiff->format("%h");
	$booking_days = date_diff(date_create($bookingDates['checkout_date']), date_create($bookingDates['checkin_date']))->format("%a");
	$amount = $bookingDates['total'];
	if ($bookingDates['payment_option'] != 'arrival') {
		if ($cancelDateDifferenceHours >= 168) {
			$refund_charge = 0.00;
			$refund_amount = $amount;
		} else if ($cancelDateDifferenceHours < 168 && $cancelDateDifferenceHours >= 48) {
			$refund_charge = ($amount * 50) / 100;
			$refund_amount = $amount - $refund_charge;
		} else if ($cancelDateDifferenceHours < 48) {
			$refund_charge = $amount;
			$refund_amount = 0.00;
		}
	} else {
		$refund_charge = $amount;
		$refund_amount = 0.00;
	}
	/* Cancel Amount Checking End*/





	/*$data1 = [
		'id_booking' => $id_booking,
		'id_room' => $id_room,
		'reason' => $reason,
		'booking_amount' => $amount,
		'refund_charge' => '0.00',
		'refund_amount' => $amount,
	];
	$sql_insert = "INSERT INTO pm_booking_cancel(id_booking, id_room, reason, days_before, booking_amount, refund_charge, refund_amount)
VALUES(:id_booking, :id_room, :reason, :booking_amount, :refund_charge, :refund_amount)";
	$stmt1 = $db->prepare($sql_insert);
	$stmt1 = $stmt1->execute($data1);*/

	$db->query("INSERT INTO `pm_booking_cancel`(`id_booking`, `id_room`, `reason`, `days`, `booking_amount`, `refund_charge`, `refund_amount`) 
	VALUES ('" . $id_booking . "', '" . $id_room . "', '" . $reason . "', '" . $booking_days . "', '" . $amount . "', '" . $refund_charge . "', '" . $refund_amount . "')");
	if (isset($_POST['booking_id'])) {
		$data = [
			'status' => 2,
			'id' => $id_booking
		];
		$sql = "UPDATE pm_booking SET status=:status WHERE id=:id";
		$stmt = $db->prepare($sql);
		$stmt->execute($data);
		$purpose = 'Booking Id ' . $id_booking . ' Cancelled on ' . date('d-m-Y') . '. Reason : ' . $reason . ' - Offline mode';
		// Add activity log
		add_activity_log($_SESSION['admin']['id'], $id_booking, 'booking', 'cancel', $purpose);
		//wallet process
		if ($status == 4) {
			// walletUpdate($id_booking, $result_booking['id_user'], $refund_amount, 'credit', 'cancel');
		}
		echo '<div class="alert alert-success">
	      <strong>Success!</strong> Your Booking is successfully cancelled.Please contact Hotel Administrator for refunds if any.
       </div>';
		$subject = 'Your Booking is Cancelled';
		$subject1 = 'Booking is Cancelled';
		//$wallet_subject = 'Wallet Credit';
		//$wallet_content = '<tr><td style="text-align: center;padding: 0 20px 20px 20px;background: #fff;"><p style="color: #000;font-size: 22px;margin: 0;">YOUR BOOKING IS CANCELLED!</p></td></tr><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;"><tr><td colspan="2" style="padding: 0 20px;"><p style="margin: 0 0 20px;">We credit'.formatPrice($refund_amount*CURRENCY_RATE).' in your account wallet.</p></td></tr></table><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;"><tr><td style="padding: 20px;background: #fff;"><h2>POLICIES</h2><ul><li>Standard Check-In Time - 12:00 PM </li><li>Standard Check-Out Time - 11:00 AM </li><li>Early Check-In/ Late Check-Out - (a) Our standard check-in/ check-out time is 12 pm/11:00 am respectively. (b) Thanks to our loyal guests like yourself, most of the rooms at our properties are sold out throughout the year. Unfortunately, as much as we would like to, we are not in a position to guarantee an early check-in or a late check-out. However, we will certainly try to offer you an early check-in (free of charge) from 9 am to 12 pm as long as we do not have other guests already staying in the booked room, or a late check out up to 2 pm (free of charge) provided we do not have other guests arriving immediately. Complimentary breakfast is not served on the day of check-in. </li><li>Identification Card - All adults must carry one of these photo ID cards at the time of check-in: Driving License, Voters Card, Passport, Ration Card or Aadhar Card. PAN Cards are not accepted</li><li>Cancellation Policy - No cancellation fee is charged if the booking is cancelled 24 hours prior to the standard check-in time. </li><li>If we are not able to reach you for reconfirmation, we reserve the rights to cancel your booking. </li><li>Other Policies - Our hotels reserve the right of admission to ensure safety and comfort of guests. This may include cases such as local residents, unmarried and unrelated couples among others. </li></ul></td></tr></table>';
		//$content1 = '<tr><td style="text-align: center;padding: 0 20px 20px 20px;background: #fff;"><p style="color: #000;font-size: 22px;margin: 0;">YOUR BOOKING IS CANCELLED!</p></td></tr><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;"><tr><td style="padding: 20px;background: #fff;"><h2 style="font-weight: bold;color: #797979;font-size: 18px;margin: 0;">Booking ID : '.$result_booking['id'].'</h2></td><td style="padding: 20px;background: #fff;text-align: right;"><h2 style="font-weight: bold;color: #797979;font-size: 18px;margin: 0;">Booking Date : '.gmstrftime(DATE_FORMAT, $result_booking['add_date']).'</h2></td></tr><tr><td colspan="2" style="padding: 0 20px;"><p style="margin: 0 0 20px;">As per your request, your booking is cancelled. We hope to serve you soon in the future.</p><p><b>Total Amount<b> : '.formatPrice($amount*CURRENCY_RATE).'<p><p><b>Refund Amount</b> : '.formatPrice($amount*CURRENCY_RATE).'</p></td></tr></table><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;"><tr><td style="padding: 20px;background: #fff;"><h2 style="font-weight: bold;color: #797979;font-size: 18px;margin: 0 0 20px;">Check In</h2><p style="text-transform: uppercase;font-size: 18px;color: #587286;margin: 0;">'.gmstrftime(DATE_FORMAT, $result_booking['from_date']).'</p></td><td style="padding: 20px;background: #fff;text-align: right;"><h2 style="font-weight: bold;color: #797979;font-size: 18px;margin: 0 0 20px;">Check Out</h2><p style="text-transform: uppercase;font-size: 18px;color: #587286;margin: 0;">'.gmstrftime(DATE_FORMAT, $result_booking['to_date']).'</p></td></tr></table><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;"><tr><td style="padding: 20px;background: #fff;"><h2 style="font-weight: bold;color: #797979;font-size: 18px;margin: 0 0 20px;">No. of Guests</h2></td></tr></table><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;"><tr><td style="padding: 20px;background: #fff;"><h2>POLICIES</h2><ul><li>Standard Check-In Time - 12:00 PM </li><li>Standard Check-Out Time - 11:00 AM </li><li>Early Check-In/ Late Check-Out - (a) Our standard check-in/ check-out time is 12 pm/11:00 am respectively. (b) Thanks to our loyal guests like yourself, most of the rooms at our properties are sold out throughout the year. Unfortunately, as much as we would like to, we are not in a position to guarantee an early check-in or a late check-out. However, we will certainly try to offer you an early check-in (free of charge) from 9 am to 12 pm as long as we do not have other guests already staying in the booked room, or a late check out up to 2 pm (free of charge) provided we do not have other guests arriving immediately. Complimentary breakfast is not served on the day of check-in. </li><li>Identification Card - All adults must carry one of these photo ID cards at the time of check-in: Driving License, Voters Card, Passport, Ration Card or Aadhar Card. PAN Cards are not accepted</li><li>Cancellation Policy - No cancellation fee is charged if the booking is cancelled 24 hours prior to the standard check-in time. </li><li>If we are not able to reach you for reconfirmation, we reserve the rights to cancel your booking. </li><li>Other Policies - Our hotels reserve the right of admission to ensure safety and comfort of guests. This may include cases such as local residents, unmarried and unrelated couples among others. </li></ul></td></tr></table>';
		$content1 = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;"><p style="margin: 20px 0 20px;">Your booking ' . $result_booking['id'] . ' at HMS for check-in date: ' . gmstrftime(DATE_FORMAT, $result_booking['from_date']) . ' has been cancelled.</p>
    			<p style="margin: 0 0 20px;">We hope to host you in the future.</p>
    			<p style="margin: 0 0 20px;">Cheers <br>Team HMS </p></td></tr>';

		$content = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;"><p style="margin: 20px 0 20px;">Your booking ' . $result_booking['id'] . ' at HMS for check-in date: ' . gmstrftime(DATE_FORMAT, $result_booking['from_date']) . ' has been cancelled.</p>
    			<p style="margin: 0 0 20px;">We hope to host you in the future.</p>
    			<p style="margin: 0 0 20px;">Cheers <br>Team HMS </p></td></tr>';
		$row = $db->query('SELECT * FROM pm_booking WHERE id = ' . $db->quote($_POST['booking_id']))->fetch();
		//SMS Notification
		if ($row['id_user'] > 0) {
			$mobile = db_getFieldValue($db, 'pm_user', 'mobile', $row['id_user'], $lang = 0);
			if ($mobile != "") {
				$message = 'Hi ' . $row['firstname'] . ' Your booking # ' . $id_booking . ' has been successfully cancelled';
				httpPost($mobile, $message);
			}
		}
		$from_email = 'cancellation@hms.com';
		$from_name = 'HMS';
		sendMail(EMAIL, OWNER, $subject1, $content1, $row['email'], $row['firstname'] . ' ' . $row['lastname'], $from_email, $from_name);
		sendMail($row['email'], $row['firstname'] . ' ' . $row['lastname'], $subject, $content, '', '', $from_email, $from_name);
		if ($status == 4) {
			//sendMail($row['email'], $row['firstname'].' '.$row['lastname'], $wallet_subject, $wallet_content);
		}
	} else {
		echo '<div class="alert alert-danger">
	<strong>Error!</strong> Something wents wrong.please try after some time.
</div>';
	}
}
exit;
