<?php
require_once('../../../common/lib.php');
require_once('../../../common/define.php');
require_once '../../../includes/dompdf/lib/html5lib/Parser.php';
require_once '../../../includes/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once '../../../includes/dompdf/lib/php-svg-lib/src/autoload.php';
require_once '../../../includes/dompdf/src/Autoloader.php';
require_once('functions.php');
if (isset($_POST['booking_id']) && isset($_SESSION['user']['id']) && isset($_POST['reason'])) {

	$booking_detail = $db->query("SELECT * FROM pm_booking_room AS pbm WHERE pbm.`id_booking` = " . $_POST['booking_id'])->fetch();
	$booked_room = $db->query("SELECT * FROM pm_booking_room AS pbm WHERE pbm.`id_booking` = " . $_POST['booking_id'] . " ORDER BY id DESC ")->fetchAll();
	$rcount = count($booked_room);
	$room_detail = $db->query("SELECT * FROM pm_room AS pr WHERE pr.`id` = " . $booking_detail['id_room'])->fetch();
	$reason = $_POST['reason'];
	$id_booking = $booking_detail['id_booking'];
	$amount = $db->query("SELECT * FROM pm_booking WHERE id = " . $id_booking)->fetch()['amount'];
	$id_room = $booking_detail['id_room'];

	$cancel_fees = $_POST['cancel_fees'];
	$fees_type = $_POST['fees_type'];
	$result_booking = $db->query('SELECT * FROM pm_booking WHERE id = ' . $id_booking)->fetch();
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$status = $result_booking['status'];
	//echo $_POST['from_date'];
	$dat1 = explode('/', $_POST['from_date']);
	$date1  = gm_strtotime($dat1[2] . '-' . $dat1[1] . '-' . $dat1[0] . ' 00:00:00');
	//echo '  || ' ;
	$dat2 =  explode('/', $_POST['to_date']);
	$date2  = gm_strtotime($dat2[2] . '-' . $dat2[1] . '-' . $dat2[0] . ' 00:00:00');

	$postFromDate = date_format(date_create($_POST['from_date']), 'Y-m-d H:i:s');
	$postToDate = date_format(date_create($_POST['to_date']), 'Y-m-d H:i:s');

	$datesBetween = $_POST['from_date'] . ' - ' . $_POST['to_date'];

	//echo '  <br>' ;
	//echo $result_booking['from_date'];
	//echo '  || ' ;
	//echo $result_booking['to_date'];

	$btax = (($result_booking['tax_amount'] * 100) / $result_booking['amount']);
	$new_from_date = $date1;
	$new_to_date = $result_booking['to_date'];

	// Formulate the Difference between two dates 
	$diff = abs($date2 - $date1);
	$days = floor($diff / (60 * 60 * 24));

	$refund_charge = '';
	$refund_amount = '';
	$stat = 0;
	$booking_amount = 0;
	if ($days == $result_booking['nights']) {
		$nights = $result_booking['nights'];
	} else {
		$nights = ($result_booking['nights'] - $days);
	}
	$cancel_elements = '';


	$bookingDates = $fromDAte = $db->query('SELECT total, FROM_UNIXTIME(from_date,"%Y-%m-%d") as checkin_date, FROM_UNIXTIME(to_date,"%Y-%m-%d") as checkout_date FROM pm_booking WHERE id = "' . $id_booking . '"')->fetch(PDO::FETCH_ASSOC);

	// full cancel
	if ($days == $result_booking['nights'] && $rcount == count($_POST['no_room'])) {
		$refund_amount = $amount;


		if ($fees_type == 'parcentage') {
			$refund_charge = (($cancel_fees * $amount) / 100);
			$refund_amount = $refund_amount - $refund_charge;
		} else {
			$refund_charge = $cancel_fees;
			$refund_amount = ($refund_amount - $refund_charge);
		}
		$cancel_type = 'full';
		$stat = 2;
		$booking_amount = $amount - $refund_amount;

		$new_from_date = $result_booking['from_date'];
		$new_to_date = $result_booking['to_date'];
		$cancel_elements = 'Full (Date ( ' . $datesBetween . ' ) + Room ( ' . $room_detail['title'] . ' ))';
	} else if ($days == $result_booking['nights'] && $rcount > count($_POST['no_room'])) {
		// only partial room cancel 
		$room_reduce = 0;
		foreach ($_POST['no_room'] as $key => $rm) {
			$room_reduce = $room_reduce + $db->query("SELECT * FROM pm_booking_room AS pbm WHERE pbm.`id` = " . $rm)->fetch()['amount'];
		}
		$refund_amount = $room_reduce;
		if ($fees_type == 'parcentage') {
			$refund_charge = (($cancel_fees * $refund_amount) / 100);
			$refund_amount = $refund_amount - $refund_charge;
		} else {
			$refund_charge = $cancel_fees;
			$refund_amount = $refund_amount - $refund_charge;
		}
		$cancel_type = 'part';
		$stat = 0;
		$booking_amount = ($amount - $refund_amount);
		$new_from_date = $result_booking['from_date'];
		$new_to_date = $result_booking['to_date'];
		$cancel_elements = 'Room ( ' . $room_detail['title'] . ' )';
	} else if ($days < $result_booking['nights'] && $rcount == count($_POST['no_room'])) {
		// onli partial day cancel
		$per_night = $amount / $result_booking['nights'];
		$refund_amount = ($per_night * $days);
		if ($fees_type == 'parcentage') {
			$refund_charge = (($cancel_fees * $refund_amount) / 100);
			$refund_amount = $refund_amount - $refund_charge;
		} else {
			$refund_charge = $cancel_fees;
			$refund_amount = $refund_amount - $refund_charge;
		}
		$cancel_type = 'part';
		$stat = 0;
		$booking_amount = ($amount - $refund_amount);
		if ($result_booking['from_date'] == $date1) {
			$new_from_date = $date2;
			$new_to_date = $result_booking['to_date'];
		} else if ($result_booking['to_date'] == $date2) {
			$new_from_date = $result_booking['from_date'];
			$new_to_date = $date1;
		}
		$cancel_elements = 'Date ( ' . $datesBetween . ' )';
	} else {
		$room_reduce = 0;
		foreach ($_POST['no_room'] as $key => $rm) {
			$room_reduce = $room_reduce + $db->query("SELECT * FROM pm_booking_room AS pbm WHERE pbm.`id` = " . $rm)->fetch()['amount'];
		}
		$refund_amount1 = $room_reduce;
		$refund_amount2 = ($amount - $refund_amount1) / $result_booking['nights'];
		$refund_amount = $refund_amount1 + ($refund_amount2 * $days);
		if ($fees_type == 'parcentage') {
			$refund_charge = (($cancel_fees * $amount) / 100);
			$refund_amount = $amount - $refund_charge;
		} else {
			$refund_charge = $cancel_fees;
			$refund_amount = $amount - $refund_charge;
		}
		$cancel_type = 'part';
		$stat = 0;
		$booking_amount = ($amount - $refund_amount);

		if ($result_booking['from_date'] == $date1) {
			$new_from_date = $date2;
			$new_to_date = $result_booking['to_date'];
		} else if ($result_booking['to_date'] == $date2) {
			$new_from_date = $result_booking['from_date'];
			$new_to_date = $date1;
		}
		$cancel_elements = 'Both (Date ( ' . $datesBetween . ' ) + Room ( ' . $room_detail['title'] . ' ))';
	}

	$tax_amount = ($booking_amount * $btax / 100);
	$total_amount = ($booking_amount + ($booking_amount * $btax / 100));
	$refund_amount = ($refund_amount + ($refund_amount * $btax / 100));
	$data1 = [
		'id_booking' => $id_booking,
		'id_room' => $id_room,
		'cancel_type' => $cancel_type,
		'reason' => $reason,
		'days' => $days,
		'rooms' => implode(',', $_POST['no_room']),
		'booking_amount' => $amount,
		'refund_charge' => $refund_charge,
		'refund_amount' => $refund_amount,
		'cancel_element' => $cancel_elements,
	];
	$sql_insert = "INSERT INTO pm_booking_cancel(id_booking, id_room, cancel_type, reason, days, rooms, booking_amount, refund_charge, refund_amount, cancel_element)
VALUES(:id_booking, :id_room, :cancel_type, :reason, :days, :rooms, :booking_amount, :refund_charge, :refund_amount, :cancel_element)";
	$stmt1 = $db->prepare($sql_insert);
	$stmt1 = $stmt1->execute($data1);

	if (count($_POST['no_room']) < $rcount) {
		foreach ($_POST['no_room'] as $kr => $room) {
			$datar = [
				'chk' => 2,
				'id' => $room
			];
			$sql = "UPDATE pm_booking_room SET chk=:chk  WHERE id=:id";
			$stmt = $db->prepare($sql);
			$stmt->execute($datar);
		}
	}



	if (isset($_POST['booking_id'])) {
		if ($stat == 0) {
			$data = [
				'total' => $booking_amount,
				'nights' => $nights,
				'from_date' => $new_from_date,
				'to_date' => $new_to_date,
				'id' => $id_booking
			];
		} else {
			$data = [
				'status' => $stat,
				'amount' => $booking_amount,
				'tax_amount' => $tax_amount,
				'total' => $total_amount,
				'nights' => $nights,
				'from_date' => $new_from_date,
				'to_date' => $new_to_date,
				'id' => $id_booking
			];
		}

		$sql = "UPDATE pm_booking SET status=:status, amount=:amount, from_date=:from_date, to_date=:to_date  WHERE id=:id";
		$stmt = $db->prepare($sql);
		$stmt->execute($data);

		$result_book = db_prepareUpdate($db, 'pm_booking', $data);
		$result_book->execute();

		//wallet process
		if ($status == 4) {
			// walletUpdate($id_booking, $result_booking['id_user'], $refund_amount, 'credit', 'cancel');
		}

		$subject = 'Your Booking is Cancelled';
		$subject1 = 'Booking is Cancelled';

		//$wallet_subject = 'Wallet Credit';
		//$wallet_content = '<tr><td style="text-align: center;padding: 0 20px 20px 20px;background: #fff;"><p style="color: #000;font-size: 22px;margin: 0;">YOUR BOOKING IS CANCELLED!</p></td></tr><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;"><tr><td colspan="2" style="padding: 0 20px;"><p style="margin: 0 0 20px;">We credit'.formatPrice($refund_amount*CURRENCY_RATE).' in your account wallet.</p></td></tr></table><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;"><tr><td style="padding: 20px;background: #fff;"><h2>POLICIES</h2><ul><li>Standard Check-In Time - 12:00 PM </li><li>Standard Check-Out Time - 11:00 AM </li><li>Early Check-In/ Late Check-Out - (a) Our standard check-in/ check-out time is 12 pm/11:00 am respectively. (b) Thanks to our loyal guests like yourself, most of the rooms at our properties are sold out throughout the year. Unfortunately, as much as we would like to, we are not in a position to guarantee an early check-in or a late check-out. However, we will certainly try to offer you an early check-in (free of charge) from 9 am to 12 pm as long as we do not have other guests already staying in the booked room, or a late check out up to 2 pm (free of charge) provided we do not have other guests arriving immediately. Complimentary breakfast is not served on the day of check-in. </li><li>Identification Card - All adults must carry one of these photo ID cards at the time of check-in: Driving License, Voters Card, Passport, Ration Card or Aadhar Card. PAN Cards are not accepted</li><li>Cancellation Policy - No cancellation fee is charged if the booking is cancelled 24 hours prior to the standard check-in time. </li><li>If we are not able to reach you for reconfirmation, we reserve the rights to cancel your booking. </li><li>Other Policies - Our hotels reserve the right of admission to ensure safety and comfort of guests. This may include cases such as local residents, unmarried and unrelated couples among others. </li></ul></td></tr></table>';
		//$content1 = '<tr><td style="text-align: center;padding: 0 20px 20px 20px;background: #fff;"><p style="color: #000;font-size: 22px;margin: 0;">YOUR BOOKING IS CANCELLED!</p></td></tr><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;"><tr><td style="padding: 20px;background: #fff;"><h2 style="font-weight: bold;color: #797979;font-size: 18px;margin: 0;">Booking ID : '.$result_booking['id'].'</h2></td><td style="padding: 20px;background: #fff;text-align: right;"><h2 style="font-weight: bold;color: #797979;font-size: 18px;margin: 0;">Booking Date : '.gmstrftime(DATE_FORMAT, $result_booking['add_date']).'</h2></td></tr><tr><td colspan="2" style="padding: 0 20px;"><p style="margin: 0 0 20px;">As per your request, your booking is cancelled. We hope to serve you soon in the future.</p><p><b>Total Amount<b> : '.formatPrice($amount*CURRENCY_RATE).'<p><p><b>Refund Amount</b> : '.formatPrice($amount*CURRENCY_RATE).'</p></td></tr></table><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;"><tr><td style="padding: 20px;background: #fff;"><h2 style="font-weight: bold;color: #797979;font-size: 18px;margin: 0 0 20px;">Check In</h2><p style="text-transform: uppercase;font-size: 18px;color: #587286;margin: 0;">'.gmstrftime(DATE_FORMAT, $result_booking['from_date']).'</p></td><td style="padding: 20px;background: #fff;text-align: right;"><h2 style="font-weight: bold;color: #797979;font-size: 18px;margin: 0 0 20px;">Check Out</h2><p style="text-transform: uppercase;font-size: 18px;color: #587286;margin: 0;">'.gmstrftime(DATE_FORMAT, $result_booking['to_date']).'</p></td></tr></table><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;"><tr><td style="padding: 20px;background: #fff;"><h2 style="font-weight: bold;color: #797979;font-size: 18px;margin: 0 0 20px;">No. of Guests</h2></td></tr></table><table cellspacing="0" cellpadding="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;"><tr><td style="padding: 20px;background: #fff;"><h2>POLICIES</h2><ul><li>Standard Check-In Time - 12:00 PM </li><li>Standard Check-Out Time - 11:00 AM </li><li>Early Check-In/ Late Check-Out - (a) Our standard check-in/ check-out time is 12 pm/11:00 am respectively. (b) Thanks to our loyal guests like yourself, most of the rooms at our properties are sold out throughout the year. Unfortunately, as much as we would like to, we are not in a position to guarantee an early check-in or a late check-out. However, we will certainly try to offer you an early check-in (free of charge) from 9 am to 12 pm as long as we do not have other guests already staying in the booked room, or a late check out up to 2 pm (free of charge) provided we do not have other guests arriving immediately. Complimentary breakfast is not served on the day of check-in. </li><li>Identification Card - All adults must carry one of these photo ID cards at the time of check-in: Driving License, Voters Card, Passport, Ration Card or Aadhar Card. PAN Cards are not accepted</li><li>Cancellation Policy - No cancellation fee is charged if the booking is cancelled 24 hours prior to the standard check-in time. </li><li>If we are not able to reach you for reconfirmation, we reserve the rights to cancel your booking. </li><li>Other Policies - Our hotels reserve the right of admission to ensure safety and comfort of guests. This may include cases such as local residents, unmarried and unrelated couples among others. </li></ul></td></tr></table>';
		$content1 = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;"><p style="margin: 20px 0 20px;">Your booking ' . $result_booking['id'] . ' at Gupta Hotels for check-in date: ' . gmstrftime(DATE_FORMAT, $result_booking['from_date']) . ' has been cancelled.</p>';
		$content1 .= '<p style="text-transform: capitalize; ">Cancel  Mode : <strong>' . $cancel_type . '</strong></p>';
		$content1 .= '<p>Cancel Reason : <strong>' . $reason . '</strong></p>';
		$content1 .= '<p>Cancellation   Charge :<strong>' . formatPrice($refund_charge * CURRENCY_RATE) . '</strong> </p>';
		$content1 .= '<p>Refound amount : <strong>' . formatPrice($refund_amount * CURRENCY_RATE) . '</strong></p>';
		$content1 .= '<p>Cancelled Date : <strong>' . date('D, jS F Y') . '</strong></p>';
		$content1 .= '<p style="margin: 0 0 20px;">We hope to host you in the future.</p>';
		$content1 .= '<p style="margin: 0 0 20px;">Cheers <br>Gupta Hotels</p></td></tr>';
		$content = '<tr><td style="padding: 20px 20px 0 20px;background: #fff;"><p style="margin: 20px 0 20px;">Your booking ' . $result_booking['id'] . ' at Gupta Hotels for check-in date: ' . gmstrftime(DATE_FORMAT, $result_booking['from_date']) . ' has been cancelled.</p>';
		$content .= '<p style="text-transform: capitalize; ">Cancel  Mode : <strong>' . $cancel_type . '</strong></p>';
		$content .= '<p>Cancel Reason : <strong>' . $reason . '</strong></p>';
		$content .= '<p>Cancellation   Charge :<strong>' . formatPrice($refund_charge * CURRENCY_RATE) . '</strong> </p>';
		$content .= '<p>Refound amount : <strong>' . formatPrice($refund_amount * CURRENCY_RATE) . '</strong></p>';
		$content .= '<p>Cancelled Date : <strong>' . date('D, jS F Y') . '</strong></p>';
		$content .= '<p style="margin: 0 0 20px;">We hope to host you in the future.</p>';
		$content .= '<p style="margin: 0 0 20px;">Cheers <br>Gupta Hotels</p></td></tr>';


		$from_email = 'cancellation@guptahotels.com';
		$from_name = 'Gupta Hotes';
		sendMail(EMAIL, OWNER, $subject1, $content1, $row['email'], $row['firstname'] . ' ' . $row['lastname'], $from_email, $from_name);
		sendMail($row['email'], $row['firstname'] . ' ' . $row['lastname'], $subject, $content, '', '', $from_email, $from_name);
		if ($status == 4) {
			//sendMail($row['email'], $row['firstname'].' '.$row['lastname'], $wallet_subject, $wallet_content);
		}
		// Add activity log
		$logPurpose = 'Booking Id ' . $result_booking['id'] . ' Cancelled on ' . date('d-m-Y') . '. Reason : ' . $reason . ' - Online mode';
		add_activity_log($result_booking['id_user'], $result_booking['id'], 'booking', 'cancel', $logPurpose);
		//SMS Notification
		if ($result_booking['id_user'] > 0) {
			$mobile = db_getFieldValue($db, 'pm_user', 'mobile', $result_booking['id_user'], $lang = 0);
			if ($mobile != "") {
				$message = 'Hi  Your booking # ' . $id_booking . ' has been successfully cancelled';
				httpPost($mobile, $message);
			}
		}
		echo '<div class="alert alert-success">
	      <strong>Success!</strong> Your Booking is successfully cancelled. Please contact Hotel Administrator for refunds if any, Refund Amount ' . formatPrice($refund_amount * CURRENCY_RATE) . '
         </div>';
	} else {
		echo '<div class="alert alert-danger">
	           <strong>Error!</strong> Something wents wrong.please try after some time.
              </div>';
	}
}
exit;
