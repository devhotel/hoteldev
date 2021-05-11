<?php

/**
 * Script called (Ajax) on customer update
 * fills the room fields in the booking form
 */
session_start();
if (!isset($_SESSION['admin'])) exit();

if (defined('DEMO')) {
	if (DEMO == 1) {
		exit();
	}
}

define('ADMIN', true);
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');

$response = array();

if (isset($_GET['booking_id']) && isset($_GET['booking_id'])) {
	$id_booking = $_GET['booking_id'];
	$data['id'] = $_GET['booking_id'];
	$data['checked_in'] = 'in';
	$result_book = db_prepareUpdate($db, 'pm_booking', $data);
	$purpose = 'Booking  ' . $data['id'] . ' checked in';
	// Add activity log
	add_activity_log($_SESSION['admin']['id'], $data['id'], 'booking', 'edit', $purpose);

	if ($result_book->execute() !== false) {
		$result_booking = $db->query('SELECT * FROM pm_booking WHERE id = ' . $id_booking)->fetch();
		//SMS Notification
		if ($result_booking['id_user'] > 0) {
			$mobile = db_getFieldValue($db, 'pm_user', 'mobile', $result_booking['id_user'], $lang = 0);
			if ($mobile != "") {
				$message = 'Your booking # ' . $id_booking . ' is successfully checked in';
				httpPost($mobile, $message);
			}
		}
		$booking_room = $db->query("SELECT *, COUNT(id_room) as no_room, SUM(adults) as to_adults, SUM(children) as to_childs , (SUM(adults)+SUM(children)) as total_guest FROM `pm_booking_room` WHERE `id_booking` = " . $id_booking . " GROUP BY id_room");

		$room_content = '';

		if ($booking_room->execute() !== false) {

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

			foreach ($booking_room as $ky => $room) {
				$room_content .= '<tr>';
				$room_content .= '<td style="padding: 20px;background: #fff;">';
				$room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">' . $room["total_guest"] . '</h2>';
				$room_content .= '</td>';
				$room_content .= '<td style="padding: 20px;background: #fff;text-align: center;">';
				$room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">' . $room["no_room"] . '</h2>';
				$room_content .= '</td>';
				$room_content .= '<td style="padding: 20px;background: #fff;text-align: right;">';
				$room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">' . $room["title"] . '</h2>';
				$room_content .= '</td>';
				$room_content .= '</tr>';
			}

			$room_content .= '</tbody>';
			$room_content .= '</table>';
		}





		$subject = 'Welcome Booking Successfuly checked in ';
		$content = '<p style="color: rgb(0, 0, 0); font-size: 16px; margin: 0px; text-align: center;">Thank you for booking with us. Have a great stay!</p>
                <table cellpadding="0" cellspacing="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;">
                	<tbody>
            		<tr>
            			<td style="padding: 20px;background: #fff;">
            			<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0;">Booking ID :' . $id_booking . '</h2>
            			</td>
            			<td style="padding: 20px;background: #fff;text-align: right;">
            			<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0;">Booking Date :' . gmstrftime(DATE_FORMAT, $result_booking['add_date']) . '</h2>
            			</td>
            		</tr>
            		 <tr>
            			<td colspan="2" style="text-align: center;">&nbsp;</td>
            		</tr>
            	</tbody>
                </table>
            	<table cellpadding="0" cellspacing="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;">
            	<tbody>
            		<tr>
            			<td style="padding: 20px;background: #fff;">
            			<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">Check In</h2>
            
            			<p style="text-transform: uppercase;font-size: 18px;color: #000;margin: 0;">' . gmstrftime(DATE_FORMAT, $result_booking['from_date']) . '</p>
            			</td>
            			<td style="padding: 20px;background: #fff;text-align: right;">
            			<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">Check Out</h2>
            
            			<p style="text-transform: uppercase;font-size: 18px;color: #000;margin: 0;">' . gmstrftime(DATE_FORMAT, $result_booking['to_date']) . '</p>
            			</td>
            		</tr>
            	</tbody>
              </table>
            </table>' . $room_content . '<p style="margin: 0 0 20px;">We hope to host you in the future.</p>
    			<p style="margin: 0 0 20px;">Cheers <br>HMS</p>';

		$from_email = 'welcome@hms.com';
		$from_name = 'HMS';
		if (sendMail($result_booking['email'], $result_booking['firstname'] . ' ' . $result_booking['lastname'], $subject, $content, '', '', $from_email, $from_name)) {

			$userId = $result_booking['id_user'];

			//Push Notification FOR Android
			$tokenSql = $db->query('SELECT * FROM pm_device WHERE user_id  = "' . $userId . '" AND device_type = 1');
			$rowToken = $tokenSql->fetch();

			$fcm_token = $rowToken['fcm_token'];
			$deviece_type = $rowToken['device_type'];
			$title = 'Booking Checked In';
			$body = 'Your booking # ' . $id_booking . ' is successfully Checked In';

			push_notify_send($fcm_token, $deviece_type, $title, $body);
			//Push Notification FOR Android

			//Notification List					
			$noti = array();
			$noti['id'] = null;
			$noti['user_id'] = $userId;
			$noti['action_id'] = $id_booking;
			$noti['notification_title'] = 'Booking Checked In';
			$noti['notification_desc'] = 'Your booking # ' . $id_booking . ' is successfully Checked In';
			$noti['action_url'] = null;
			$noti['created_ts'] = date('Y-m-d h:i:s');
			$noti['status'] = '0';

			$insertNoti = db_prepareInsert($db, 'pm_notification', $noti);
			$insertNoti->execute();
			//Notification List


			echo 1;
		}
	}
}

exit();
