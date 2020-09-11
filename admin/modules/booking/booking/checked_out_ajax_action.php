<?php

/**
 * Script called (Ajax) on customer update
 * fills the room fields in the booking form
 */
session_start();
if (!isset($_SESSION['admin'])) exit();

if (defined('DEMO') && DEMO == 1) exit();

define('ADMIN', true);
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');
require_once '../../../../includes/dompdf/lib/html5lib/Parser.php';
require_once '../../../../includes/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once '../../../../includes/dompdf/lib/php-svg-lib/src/autoload.php';
require_once '../../../../includes/dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

use Dompdf\Dompdf;


$response = array();
$attachements = array();

if (isset($_GET['booking_id']) && isset($_GET['booking_id'])) {
    $id_booking = $_GET['booking_id'];
    $data['id'] = $_GET['booking_id'];
    $data['checked_out'] = 'out';
    $result_book = db_prepareUpdate($db, 'pm_booking', $data);
    $purpose = 'Booking  ' . $data['id'] . ' checked out';
    // Add activity log
    add_activity_log($_SESSION['admin']['id'], $data['id'], 'booking', 'edit', $purpose);

    if ($result_book->execute() !== false) {
        $result_booking = $db->query('SELECT * FROM pm_booking WHERE id = ' . $id_booking)->fetch();
        //SMS Notification
        if ($result_booking['id_user'] > 0) {
            $mobile = db_getFieldValue($db, 'pm_user', 'mobile', $result_booking['id_user'], $lang = 0);
            if ($mobile != "") {
                $message = 'Your booking # ' . $id_booking . ' has been successfully checked out';
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
                $room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">' . $room["to_adults"] . ($room["to_adults"] > 1 ? ' Adults' : ' Adult') . ', ' . $room["to_childs"] . ($room["to_childs"] > 1 ? ' Kids' : ' Kid') . '</h2>';
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

        $subject = 'Booking Successfuly checked out ';
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
              </table>' . $room_content . '<table cellpadding="0" cellspacing="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;">
        	  <tbody>
        		<tr>
        			<td style="padding: 20px;background: #fff;">
        			<p>Total Cost</p>
        			<p>Discount</p>
        			<p>Taxes</p>
        			<p style="font-size: 22px;font-weight: bold;margin: 0;">TOTAL PAYABLE</p>
        			<p style="margin: 0;">At hotel</p>
        			</td>
        			<td style="padding: 20px;background: #fff;text-align: right;">
        			<p>' . formatPrice($result_booking['amount'] * CURRENCY_RATE) . '</p>
        			<p>' . formatPrice($result_booking['discount'] * CURRENCY_RATE) . '</p>
        			<p>' . formatPrice($result_booking['tax_amount'] * CURRENCY_RATE) . '</p>
        			<p style="font-size: 22px;font-weight: bold;margin: 0;">' . formatPrice($result_booking['total'] * CURRENCY_RATE) . '</p>
        			<p style="margin: 0;">Incl. of all taxes</p>
        			</td>
        		</tr>
        	  </tbody>
            </table><p style="margin: 0 0 20px;">We hope to host you in the future.</p>
    		<p style="margin: 0 0 20px;">Cheers <br>HMS</p>';


        $html = '<style>table tr td {
        
        }</style>';

        //$result_booking = $db->query('SELECT * FROM pm_booking WHERE id = '.$id_booking.' AND id_user = '.$db->quote($_SESSION['user']['id']));
        $result_booking_inv = $db->query('SELECT * FROM pm_booking WHERE id = ' . $id_booking);
        if ($result_booking_inv !== false && $db->last_row_count() > 0) {
            $row = $result_booking_inv->fetch();
            $result_hotel = $db->query('SELECT * FROM pm_hotel WHERE id = ' . $row['id_hotel'])->fetch();
            $result_hotel_file = $db->query('SELECT * FROM pm_hotel_file WHERE id_item = ' . $row['id_hotel'] . ' AND checked = 1 AND lang = ' . LANG_ID . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1')->fetch();
            $realpath = SYSBASE . 'medias/hotel/small/' . $result_hotel_file['id'] . '/' . $result_hotel_file['file'];
            $thumbpath = DOCBASE . 'medias/hotel/small/' . $result_hotel_file['id'] . '/' . $result_hotel_file['file'];
            $zoompath = DOCBASE . 'medias/hotel/big/' . $result_hotel_file['id'] . '/' . $result_hotel_file['file'];

            $html .= '
                <h2 style="text-transform: uppercase;font-weight: 600;font-family: "Montserrat"; color: #2f9df2;">' . $texts['BOOKING_SUMMARY'] . '</h2>
                
                <div class="booking_details_top" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
          <span>Booking #<strong>' . $id_booking . '</strong></span>';


            switch ($row['status']) {
                case 1:
                    $html .= '<span class="pull-right label label-primary" style="color:#fff">' . $texts['AWAITING'] . '</span>';
                    break;
                case 2:
                    $html .= '<span class="pull-right label label-danger" style="color:#fff">' . $texts['CANCELLED'] . '</span>';
                    break;
                case 3:
                    $html .= '<span class="pull-right label label-danger" style="color:#fff">' . $texts['REJECTED_PAYMENT'] . '</span>';
                    break;
                case 4:
                    $html .= '<span class="pull-right label label-success" style="color:#fff">' . $texts['PAYED'] . '</span>';
                    break;
                default:
                    $html .= '<span class="pull-right label label-primary" style="color:#fff">' . $texts['AWAITING'] . '</span>';
                    break;
            }

            $html .= '</div>
        <div class="booking_details_middle">
        <table class="table table-responsive" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
         <tr>
                        <td style="padding:15px;"><h2>' . $result_hotel['title'] . '</h2><p><strong>Hotel Address :</strong> ' . $result_hotel['address'] . ' </p></td>
          
                </tr>
          
         </table>
        </div>
                
                <table class="table table-responsive" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
                    <tr>
                        <th width="50%" style="text-align: left;text-transform: uppercase;">' . $texts['BOOKING_DETAILS'] . '</th>
                        <th width="50%" style="text-align: left;text-transform: uppercase;">' . $texts['BILLING_ADDRESS'] . '</th>
                    </tr>
                    <tr>
                        <td style="padding:15px;">
                        
                            ' . $texts['CHECK_IN'] . ' <strong>' . gmstrftime(DATE_FORMAT, $row['from_date']) . '</strong><br>
                            ' . $texts['CHECK_OUT'] . ' <strong>' . gmstrftime(DATE_FORMAT, $row['to_date']) . '</strong><br>
                            <strong>' . $row['nights'] . '</strong> ' . getAltText($texts['NIGHT'], $texts['NIGHTS'], $row['nights']) . '<br>';
            $html .= '
                        </td>
                        <td style="padding:15px;">
                            ' . $row['firstname'] . ' ' . $row['lastname'] . '<br>';
            if ($row['company'] != '') $html .= $texts['COMPANY'] . ' : ' . $row['company'] . '<br>';
            $html .= nl2br($row['address']) . '<br>
                            ' . $row['postcode'] . ' ' . $row['city'] . '<br>';
            if ($row['mobile'] != '') $html .= $texts['MOBILE'] . ' : ' . $row['mobile'] . '<br>';
            $html .= $texts['EMAIL'] . ' : ' . $row['email'] . '
                        </td>
                    </tr>
                </table>';

            $result_room = $db->query('SELECT * FROM pm_booking_room WHERE id_booking = ' . $row['id']);
            if ($result_room !== false && $db->last_row_count() > 0) {
                $html .= '
                    <table class="table table-responsive" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
                        <tr>
                            <th style="padding:15px;text-align: left; text-transform: capitalize;">' . $texts['ROOM'] . '</th>
                            <th style="padding:15px;text-align: left; text-transform: capitalize;">' . $texts['PERSON'] . '</th>
                            <th class="text-center" style="padding:15px;text-align: left; text-transform: capitalize;">' . $texts['TOTAL'] . '</th>
                        </tr>';
                foreach ($result_room as $room) {
                    $html .=
                        '<tr>
                                <td style="padding:15px;">' . $room['title'] . '</td>
                                <td style="padding:15px; text-transform: capitalize;">
                                    ' . ($room['adults'] + $room['children']) . ' ' . getAltText($texts['PERSON'], $texts['PERSON'], ($room['adults'] + $room['children'])) . ': ';
                    if ($room['adults'] > 0) $html .= $room['adults'] . ' ' . getAltText($texts['ADULT'], $texts['ADULTS'], $room['adults']) . ' ';
                    if ($room['children'] > 0) $html .= $room['children'] . ' ' . getAltText($texts['CHILD'], $texts['CHILDREN'], $room['children']) . ' ';
                    $html .= '
                                </td>
                                <td class="text-right" width="15%" style="padding:15px;">' . formatPrice($room['amount'] * CURRENCY_RATE) . '</td>
                            </tr>';
                }
                $html .= '
                    </table>';
            }

            $result_service = $db->query('SELECT * FROM pm_booking_service WHERE id_booking = ' . $row['id']);
            if ($result_service !== false && $db->last_row_count() > 0) {
                $html .= '
                    <table class="table table-responsive" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
                        <tr>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['SERVICE'] . '</th>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['QUANTITY'] . '</th>
                            <th class="text-center" style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['TOTAL'] . '</th>
                        </tr>';
                foreach ($result_service as $service) {
                    $html .=
                        '<tr>
                                <td style="padding:15px;">' . $service['title'] . '</td>
                                <td style="padding:15px;">' . $service['qty'] . '</td>
                                <td class="text-right" width="15%" style="padding:15px;">' . formatPrice($service['amount'] * CURRENCY_RATE) . '</td>
                            </tr>';
                }
                $html .= '
                    </table>';
            }

            $result_activity = $db->query('SELECT * FROM pm_booking_activity WHERE id_booking = ' . $row['id']);
            if ($result_activity !== false && $db->last_row_count() > 0) {
                $html .= '
                    <table class="table table-responsive" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
                        <tr>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['ACTIVITY'] . '</th>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['DURATION'] . '</th>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['DATE'] . '</th>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['PERSONS'] . '</th>
                            <th class="text-center" style="padding:15px;">' . $texts['TOTAL'] . '</th>
                        </tr>';
                foreach ($result_activity as $activity) {
                    $html .=
                        '<tr>
                                <td style="padding:15px;">' . $activity['title'] . '</td>
                                <td style="padding:15px;">' . $activity['duration'] . '</td>
                                <td style="padding:15px;">' . gmstrftime(DATE_FORMAT . ' ' . TIME_FORMAT, $activity['date']) . '</td>
                                <td style="padding:15px;">
                                    ' . ($activity['adults'] + $activity['children']) . ' ' . getAltText($texts['PERSON'], $texts['PERSONS'], ($activity['adults'] + $activity['children'])) . ': ';
                    if ($activity['adults'] > 0) $html .= $activity['adults'] . ' ' . getAltText($texts['ADULT'], $texts['ADULTS'], $activity['adults']) . ' ';
                    if ($activity['children'] > 0) $html .= $activity['children'] . ' ' . getAltText($texts['CHILD'], $texts['CHILDREN'], $activity['children']) . ' ';
                    $html .= '
                                </td>
                                <td class="text-right" width="15%" style="padding:15px;">' . formatPrice($activity['amount'] * CURRENCY_RATE) . '</td>
                            </tr>';
                }
                $html .= '
                    </table>';
            }
            $html .= '
                <table class="table table-responsive" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">';

            if (ENABLE_TOURIST_TAX == 1 && $row['tourist_tax'] > 0) {
                $html .= '
                        <tr>
                            <th class="text-right" style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['TOURIST_TAX'] . '</th>
                            <td class="text-right" style="padding:15px;">' . formatPrice($row['tourist_tax'] * CURRENCY_RATE) . '</td>
                        </tr>';
            }

            if (isset($row['discount']) && $row['discount'] > 0) {
                $html .= '
                        <tr>
                            <th class="text-right" style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['DISCOUNT'] . '</th>
                            <td class="text-right" style="padding:15px;">- ' . formatPrice($row['discount'] * CURRENCY_RATE) . '</td>
                        </tr>';
            }

            $result_tax = $db->query('SELECT * FROM pm_booking_tax WHERE id_booking = ' . $row['id']);
            if ($result_tax !== false && $db->last_row_count() > 0) {
                foreach ($result_tax as $tax) {

                    $html .= '
                            <tr>
                                <th class="text-right" style="padding:15px;text-align: left;text-transform: uppercase;">' . $tax['name'] . '</th>
                                <td class="text-right" style="padding:15px;">' . formatPrice($tax['amount'] * CURRENCY_RATE) . '</td>
                            </tr>';
                }
            }

            $html .= '
                    <tr>
                        <th class="text-right" style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['TOTAL'] . ' (' . $texts['INCL_TAX'] . ')</th>
                        <td class="text-right" width="15%" style="padding:15px;"><b>' . formatPrice($row['total'] * CURRENCY_RATE) . '</b></td>
                    </tr>';

            if (ENABLE_DOWN_PAYMENT == 1 && $row['down_payment'] > 0) {
                $html .= '
                        <tr>
                            <th class="text-right" style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['DOWN_PAYMENT'] . ' (' . $texts['INCL_TAX'] . ')</th>
                            <td class="text-right" width="15%" style="padding:15px;"><b>' . formatPrice($row['down_payment'] * CURRENCY_RATE) . '</b></td>
                        </tr>';
            }
            $html .= '
                </table>';

            $html .= '<p><strong>' . $texts['PAYMENT'] . '</strong><p>';

            $html .= '<p>' . $texts['PAYMENT_METHOD'] . ' : ' . $row['payment_option'] . '<br>';
            $html .= $texts['STATUS'] . ': ';
            switch ($row['status']) {
                case 1:
                    $html .= '<span class="label label-primary">' . $texts['AWAITING'] . '</span>';
                    break;
                case 2:
                    $html .= '<span class="label label-danger">' . $texts['CANCELLED'] . '</span>';
                    break;
                case 3:
                    $html .= '<span class="label label-danger">' . $texts['REJECTED_PAYMENT'] . '</span>';
                    break;
                case 4:
                    $html .= '<span class="label label-success">' . $texts['PAYED'] . '</span>';
                    break;
                default:
                    $html .= '<span class="label label-primary">' . $texts['AWAITING'] . '</span>';
                    break;
            }
            $html .= '<br>';

            $result_payment = $db->query('SELECT * FROM pm_booking_payment WHERE id_booking = ' . $row['id']);
            if ($result_payment !== false && $db->last_row_count() > 0) {
                $html .= '
                  <table class="table table-responsive" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
                    <tr>
                      <th style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['DATE'] . '</th>
                      <th style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['PAYMENT_METHOD'] . '</th>
                      <th class="text-center" style="padding:15px;text-align: left;text-transform: uppercase;">' . $texts['AMOUNT'] . '</th>
                    </tr>';
                foreach ($result_payment as $payment) {
                    $html .=
                        '<tr>
                        <td style="padding:15px;">' . gmstrftime(DATE_FORMAT . ' ' . TIME_FORMAT, $payment['date']) . '</td>
                        <td style="padding:15px;">' . $payment['method'] . '</td>
                        <td class="text-right" width="15%" style="padding:15px;">' . formatPrice($payment['amount'] * CURRENCY_RATE) . '</td>
                      </tr>';
                }
                $html .= '
                  </table>';
            }

            if ($row['status'] == 4) {
                $html .= $texts['PAYMENT_DATE'] . ' : ' . gmstrftime(DATE_FORMAT . ' ' . TIME_FORMAT, $row['payment_date']) . '<br>';
                if (!empty($row['down_payment'])) $html .= $texts['DOWN_PAYMENT'] . ' : ' . formatPrice($row['down_payment'] * CURRENCY_RATE) . '<br>';
                if (!empty($row['trans'])) $html .= $texts['NUM_TRANSACTION'] . ' : ' . $row['trans'];
            }
            $html .= '</p>';
        }

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        $file_name = $id_booking . '.pdf';
        $pdfpath = SYSBASE . 'medias/booking/' . $file_name;
        file_put_contents($pdfpath, $output);
        //echo DOCBASE.'medias/booking/'.$file_name;
        DOCBASE . 'medias/booking/' . $file_name;

        $attachements[] = $pdfpath;


        $from_email = 'eelcome@hms.com';
        $from_name = 'HMS';
        //sendMail($recipient_email, $recipient_name, $subject, $content, $reply_email = '', $reply_name = '', $from_email = '', $from_name = '', $attachements = array(), $emails_copy = '')
        if (sendMail($result_booking['email'], $result_booking['firstname'] . ' ' . $result_booking['lastname'], $subject, $content, '', '', $from_email, $from_name, $attachements)) {

            $userId = $result_booking['id_user'];

            //Push Notification FOR Android
            $tokenSql = $db->query('SELECT * FROM pm_device WHERE user_id  = "' . $userId . '" AND device_type = 1');
            $rowToken = $tokenSql->fetch();

            $fcm_token = $rowToken['fcm_token'];
            $deviece_type = $rowToken['device_type'];
            $title = 'Booking Checked Out';
            $body = 'Your booking # ' . $id_booking . ' is successfully Checked Out';

            push_notify_send($fcm_token, $deviece_type, $title, $body);
            //Push Notification FOR Android

            //Notification List					
            $noti = array();
            $noti['id'] = null;
            $noti['user_id'] = $userId;
            $noti['action_id'] = $id_booking;
            $noti['notification_title'] = 'Booking Checked Out';
            $noti['notification_desc'] = 'Your booking # ' . $id_booking . ' is successfully Checked Out';
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
