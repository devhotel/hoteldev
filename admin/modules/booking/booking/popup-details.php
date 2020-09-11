<?php
define("ADMIN", true);
define("SYSBASE", str_replace("\\", "/", realpath(dirname(__FILE__) . "/../../../../") . "/"));
require_once(SYSBASE . "common/lib.php");
require_once(SYSBASE . "common/define.php");

if (!isset($_SESSION['admin'])) exit();
elseif ($_SESSION['admin']['type'] == "registered") {
    unset($_SESSION['admin']);
    exit();
} else {
}

?>
<html>
<?php
if (isset($_POST['id']) && isset($_SESSION['admin']['id'])) {
    $id_booking = (int) $_POST['id'];
    if (is_numeric($id_booking)) {
?>
        <head>
            <?php include(SYSBASE . ADMIN_FOLDER . '/includes/inc_header_list.php'); ?>
            <script>
                function printElem(elem) {
                    var popup = window.open('', 'print', 'height=800,width=600');
                    popup.document.write('<html><head><title>' + document.title + '</title><link rel="stylesheet" href="<?php echo DOCBASE . ADMIN_FOLDER . '/css/print.css'; ?>"/></head><body>' + document.getElementById(elem).innerHTML + '</body></html>');
                    setTimeout(function() {
                        popup.document.close();
                        popup.focus();
                        popup.print();

                    }, 600);
                    return true;
                }
            </script>
            <style>
                .white-popup-block {
                    width: 850px;
                    max-width: none;
                }
            </style>
        </head>
        <body>
            <div class="white-popup-block">
                <?php
                $todate = strtotime(date("Y-m-d")) + 19800;
                $result_booking = $db->query('SELECT * FROM pm_booking WHERE id = ' . $id_booking);
                if ($result_booking !== false && $db->last_row_count() > 0) {
                    $row = $result_booking->fetch();
                    //echo $row['from_date'];
                ?>
                    <br />
                    <div class="pull-right">
                        <?php if ($row['status'] != 2) {
                            //if ($row['checked_in'] != 'in' && $row['checked_out'] != 'out') { 
                            if ($row['checked_out'] != 'out') { ?>
                                <a href="index.php?view=form&id=<?php echo $id_booking; ?>" class="btn btn-info  action-btn"><i class="fas fa-fw fa-edit"></i></a>
                        <?php }
                        } ?>
                        <a href="#" onclick="javascript:printElem('popup-booking-<?php echo $id_booking; ?>');return false;" class="btn print-btn  btn-primary"><i class="fa fa-print"></i></a>
                        <?php
                        $chekInDispStat = '';
                        if ($row['status'] != 2 && $todate >= $row['from_date']) {
                            if ($row['checked_in'] == 'in') {
                                $chekInDispStat = 'style = "display:none;"';
                            }
                        ?>
                            <?php if((strtotime(date("Y-m-d H:i:s")) + 19800) >= $row['from_date'] && strtotime((date("Y-m-d H:i:s")) + 19800) <= $row['to_date']){
                                if ($_SESSION['admin']['id'] == 1 || $_SESSION['admin']['id'] == 4) {?>
                            <a href="javascript:void(0);" onclick="checkedIndhandler();" id="checked_in" <?= $chekInDispStat ?> class="btn btn-success">Check in</a>
                            <?php } } ?>
                            <input type="hidden" id="ifPaid" value="<?= $row['paid'] ?>">
                        <?php } ?>
                        <?php if ($_SESSION['admin']['id'] == 1 || $_SESSION['admin']['id'] == 4) { ?>
                            <a href="javascript:void(0);" onclick="checkedOuthandler();" id="checked_out" <?php echo (($row['checked_out'] != 'out' && $row['checked_in'] == 'in' && $row['status'] == 4) ? '' : 'style="display:none"'); ?> class="btn  btn-success">Check Out</a>
                        <?php } ?>
                        <?php if ($row['status'] == 1) {
                            if ($row['checked_in'] == '' && $row['checked_out'] == '') { ?>
                                <a href="cancel_popup.php" data-params="id=<?php echo $id_booking; ?>" class="dropdown-item ajax-popup-link tips btn  btn-danger">Cancel</a>
                        <?php }
                        } ?>
                    </div>
                    <div id="popup-booking-<?php echo $id_booking; ?>">
                        <?php
                        $result_hotel = $db->query('SELECT * FROM pm_hotel WHERE id = ' . $row['id_hotel'])->fetch();
                        $result_hotel_file = $db->query('SELECT * FROM pm_hotel_file WHERE id_item = ' . $row['id_hotel'] . ' AND checked = 1 AND lang = ' . LANG_ID . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1')->fetch();
                        $realpath = SYSBASE . 'medias/hotel/small/' . $result_hotel_file['id'] . '/' . $result_hotel_file['file'];
                        $thumbpath = DOCBASE . 'medias/hotel/small/' . $result_hotel_file['id'] . '/' . $result_hotel_file['file'];
                        $zoompath = DOCBASE . 'medias/hotel/big/' . $result_hotel_file['id'] . '/' . $result_hotel_file['file'];
                        echo '
                <h2>' . $texts['BOOKING_SUMMARY'] . ' #' . $id_booking . '</h2>
                <div class="booking_details_top">
					<span>Booking ID <br><strong>' . $id_booking . '</strong></span>'; ?>
                        <div class="checked_status pull-right" id="stat_checked">
                            <?php if ($row['status'] != 2 && $row['checked_out'] == 'out') {
                                echo '<span class="label label-success">Checked out</span>';
                            } else if ($row['status'] != 2 && $row['checked_in'] == 'in') {
                                echo '<span class="label label-default">Checked In</span>';
                            } ?>
                        </div>
                        <?php switch ($row['status']) {
                            case 1:
                                echo '<span class="pull-right label label-warning" style="color:#fff">' . $texts['AWAITING'] . '</span>';
                                break;
                            case 2:
                                echo '<span class="pull-right label label-danger" style="color:#fff">' . $texts['CANCELLED'] . '</span>';
                                break;
                            case 3:
                                echo '<span class="pull-right label label-danger" style="color:#fff">' . $texts['REJECTED_PAYMENT'] . '</span>';
                                break;
                            case 4:
                                echo '<span class="pull-right label label-success" style="color:#fff">' . $texts['PAYED'] . '</span>';
                                break;
                            default:
                                echo '<span class="pull-right label label-warning" style="color:#fff">' . $texts['AWAITING'] . '</span>';
                                break;
                        }
                        echo '</div>
				<div class="booking_details_middle">
                    <table class="table table-responsive ">
                        <tr>
                            <td><h2>' . $result_hotel['title'] . '</h2>
                                <p><strong>Hotel Address :</strong> ' . $result_hotel['address'] . ' </p>
                            </td>
                            <td style="text-align: right;"> <img src="' . $thumbpath . '" alt=""></td>
                        </tr>
                    </table>
				</div>
                <table class="table table-responsive table-bordered">
                    <tr>
                        <th width="50%">' . $texts['BOOKING_DETAILS'] . '</th>
                        <th width="50%">' . $texts['BILLING_ADDRESS'] . '</th>
                    </tr>
                    <tr>
                        <td>
                            ' . $texts['CHECK_IN'] . ' <strong>' . gmstrftime(DATE_FORMAT, $row['from_date']) . '</strong><br>
                            ' . $texts['CHECK_OUT'] . ' <strong>' . gmstrftime(DATE_FORMAT, $row['to_date']) . '</strong><br>
                            <strong>' . $row['nights'] . '</strong> ' . getAltText($texts['NIGHT'], $texts['NIGHTS'], $row['nights']) . '<br>';
                        echo '
                        </td>
                        <td>
                            ' . $row['firstname'] . ' ' . $row['lastname'] . '<br>';
                        if ($row['company'] != '') echo $texts['COMPANY'] . ' : ' . $row['company'] . '<br>';
                        echo nl2br($row['address']) . '<br>
                            ' . $row['postcode'] . ' ' . $row['city'] . '<br>
                            ' . $texts['PHONE'] . ' : ' . $row['phone'] . '<br>';
                        if ($row['mobile'] != '') echo $texts['MOBILE'] . ' : ' . $row['mobile'] . '<br>';
                        echo $texts['EMAIL'] . ' : ' . $row['email'] . '
                        </td>
                    </tr>
                </table>';
                        $offer_count = 0;
                        $result_offer = $db->query('SELECT * FROM pm_booking_offer WHERE id_booking = ' . $row['id']);
                        //var_dump($result_offer);
                        $offer_count = $db->last_row_count();
                        if ($result_offer !== false && $offer_count > 0) {
                            echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>Offer</th>
                            <th>Detail</th>
                            <th class="text-center">' . $texts['TOTAL'] . '</th>
                        </tr>';
                            foreach ($result_offer as $offer) {
                                echo
                                    '<tr>
                                <td>' . $offer['title'] . '</td>
                                <td>
                                    ' . ($offer['adults'] + $offer['children']) . ' ' . getAltText($texts['PERSON'], $texts['PERSONS'], ($offer['adults'] + $offer['children'])) . ' (';
                                if ($offer['adults'] > 0) echo $offer['adults'] . ' ' . getAltText($texts['ADULT'], $texts['ADULTS'], $offer['adults']) . ' ';
                                if ($offer['children'] > 0) echo $offer['children'] . ' ' . getAltText($texts['CHILD'], $texts['CHILDREN'], $offer['children']) . ' ';
                                if (($offer['num'] + 1) > 0) echo ($offer['num'] + 1) . ' ' . getAltText($texts['DAY'], $texts['DAYS'], ($offer['num'] + 1)) . ' ';
                                if ($offer['num'] > 0) echo $offer['num'] . ' ' . getAltText($texts['NIGHT'], $texts['NIGHTS'], $offer['num']) . ' ';
                                echo ')
                                </td>
                                <td class="text-right" width="15%">' . formatPrice($offer['amount'] * CURRENCY_RATE) . '</td>
                            </tr>';
                            }
                            echo '
                    </table>';
                        }

                        $result_room = $db->query('SELECT * FROM pm_booking_room WHERE id_booking = ' . $row['id']);
                        if ($result_room !== false && $db->last_row_count() > 0) {
                            echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>' . $texts['ROOM'] . '</th>
                            <th>' . $texts['PERSONS'] . '</th>
                            <th class="text-center">';
                            if ($offer_count == 0) {
                                echo  $texts['TOTAL'];
                            }
                            echo '</th>
                        </tr>';
                            foreach ($result_room as $room) {
                                echo
                                    '<tr>
                                <td>' . $room['title'] . '</td>
                                <td>';
                                if ($offer_count == 0) {
                                    echo ($room['adults'] + $room['children']) . ' ' . getAltText($texts['PERSON'], $texts['PERSONS'], ($room['adults'] + $room['children'])) . ' (';
                                    if ($room['adults'] > 0) echo $room['adults'] . ' ' . getAltText($texts['ADULT'], $texts['ADULTS'], $room['adults']) . ' ';
                                    if ($room['children'] > 0) echo $room['children'] . ' ' . getAltText($texts['CHILD'], $texts['CHILDREN'], $room['children']) . ' ';
                                    echo ')';
                                }
                                echo '</td>
                                <td class="text-right" width="15%">';
                                if ($offer_count == 0) {
                                    echo formatPrice($room['amount'] * CURRENCY_RATE);
                                }
                                echo  '</td>
                            </tr>';
                            }
                            echo '
                    </table>';
                        }

                        $result_service = $db->query('SELECT * FROM pm_booking_service WHERE id_booking = ' . $row['id']);
                        if ($result_service !== false && $db->last_row_count() > 0) {
                            echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>' . $texts['SERVICES'] . '</th>
                            <th>' . $texts['QUANTITY'] . '</th>
                            <th class="text-center">' . $texts['TOTAL'] . '</th>
                        </tr>';
                            foreach ($result_service as $service) {
                                echo
                                    '<tr>
                                <td>' . $service['title'] . '</td>
                                <td>' . $service['qty'] . '</td>
                                <td class="text-right" width="15%">' . formatPrice($service['amount'] * CURRENCY_RATE) . '</td>
                            </tr>';
                            }
                            echo '
                    </table>';
                        }

                        $result_activity = $db->query('SELECT * FROM pm_booking_activity WHERE id_booking = ' . $row['id']);
                        if ($result_activity !== false && $db->last_row_count() > 0) {
                            echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>' . $texts['ACTIVITY'] . '</th>
                            <th>' . $texts['DURATION'] . '</th>
                            <th>' . $texts['DATE'] . '</th>
                            <th>' . $texts['PERSONS'] . '</th>
                            <th class="text-center">' . $texts['TOTAL'] . '</th>
                        </tr>';
                            foreach ($result_activity as $activity) {
                                echo
                                    '<tr>
                                <td>' . $activity['title'] . '</td>
                                <td>' . $activity['duration'] . '</td>
                                <td>' . gmstrftime(DATE_FORMAT . ' ' . TIME_FORMAT, $activity['date']) . '</td>
                                <td>
                                    ' . ($activity['adults'] + $activity['children']) . ' ' . getAltText($texts['PERSON'], $texts['PERSONS'], ($activity['adults'] + $activity['children'])) . ' (';
                                if ($activity['adults'] > 0) echo $activity['adults'] . ' ' . getAltText($texts['ADULT'], $texts['ADULTS'], $activity['adults']) . ' ';
                                if ($activity['children'] > 0) echo $activity['children'] . ' ' . getAltText($texts['CHILD'], $texts['CHILDREN'], $activity['children']) . ' ';
                                echo ')
                                </td>
                                <td class="text-right" width="15%">' . formatPrice($activity['amount'] * CURRENCY_RATE) . '</td>
                            </tr>';
                            }
                            echo '
                    </table>';
                        }
                        echo '
                <table class="table table-responsive table-bordered">';

                        /*if(ENABLE_TOURIST_TAX == 1 && $row['tourist_tax'] > 0){
                        echo '
                        <tr>
                            <th class="text-right">'.$texts['TOURIST_TAX'].'</th>
                            <td class="text-right">'.formatPrice($row['tourist_tax']*CURRENCY_RATE).'</td>
                        </tr>';
                    }*/

                        if (isset($row['discount']) && $row['discount'] > 0) {
                            if($row['coupon_used'] != '' || $row['coupon_used'] != NULL){
                                $coupon = '('. $row['coupon_used'] .')';
                            }else{
                                $coupon = '';
                            }
                            echo '
                        <tr>
                            <th class="text-right">' . $texts['DISCOUNT'] . $coupon . '</th>
                            <td class="text-right">- ' . formatPrice($row['discount'] * CURRENCY_RATE) . '</td>
                        </tr>';
                        }

                        $result_tax = $db->query('SELECT * FROM pm_booking_tax WHERE id_booking = ' . $row['id']);
                        if ($result_tax !== false && $db->last_row_count() > 0) {
                            foreach ($result_tax as $tax) {
                                $pmtax = $db->query('SELECT value FROM pm_tax  WHERE id = ' . $tax['id_tax'] . ' AND lang=2');
                                if ($pmtax !== false && $db->last_row_count() > 0) {
                                    $dtax = $pmtax->fetch();
                                    echo '
                                <tr>
                                    <th class="text-right">' . $tax['name'] . ' (' . $dtax['value'] . '%)</th>
                                    <td class="text-right">' . formatPrice($tax['amount'] * CURRENCY_RATE) . '</td>
                                </tr>';
                                }
                            }
                        }

                        echo '
                    <tr>
                        <th class="text-right">' . $texts['TOTAL'] . ' (' . $texts['INCL_TAX'] . ')</th>
                        <td class="text-right" width="15%"><b>' . formatPrice($row['total'] * CURRENCY_RATE) . '</b></td>
                    </tr>';

                        if (ENABLE_DOWN_PAYMENT == 1 && $row['down_payment'] > 0) {
                            echo '
                        <tr>
                            <th class="text-right">' . $texts['DOWN_PAYMENT'] . ' (' . $texts['INCL_TAX'] . ')</th>
                            <td class="text-right" width="15%"><b>' . formatPrice($row['down_payment'] * CURRENCY_RATE) . '</b></td>
                        </tr>';
                        }
                        echo '
                </table>';

                        echo '<p><strong>' . $texts['PAYMENT'] . '</strong><p>';

                        echo '<p>' . $texts['PAYMENT_METHOD'] . ' : ' . $row['payment_option'] . '<br>';
                        echo $texts['STATUS'] . ': ';
                        switch ($row['status']) {
                            case 1:
                                echo '<span class="label label label-warning">' . $texts['AWAITING'] . '</span>';
                                break;
                            case 2:
                                echo '<span class="label label-danger">' . $texts['CANCELLED'] . '</span>';
                                break;
                            case 3:
                                echo '<span class="label label-danger">' . $texts['REJECTED_PAYMENT'] . '</span>';
                                break;
                            case 4:
                                echo '<span class="label label-success">' . $texts['PAYED'] . '</span>';
                                break;
                            default:
                                echo '<span class="label label label-warning">' . $texts['AWAITING'] . '</span>';
                                break;
                        }
                        echo '<br>';

                        $result_payment = $db->query('SELECT * FROM pm_booking_payment WHERE id_booking = ' . $row['id']);
                        if ($result_payment !== false && $db->last_row_count() > 0) {
                            echo '
					<table class="table table-responsive table-bordered">
						<tr>
							<th>' . $texts['DATE'] . '</th>
							<th>' . $texts['PAYMENT_METHOD'] . '</th>
							<th class="text-center">' . $texts['AMOUNT'] . '</th>
						</tr>';
                            foreach ($result_payment as $payment) {
                                echo
                                    '<tr>
								<td>' . gmstrftime(DATE_FORMAT . ' ' . TIME_FORMAT, $payment['date']) . '</td>
								<td>' . $payment['method'] . '</td>
								<td class="text-right" width="15%">' . formatPrice($payment['amount'] * CURRENCY_RATE) . '</td>
							</tr>';
                            }
                            echo '
					</table>';
                        }

                        $result_cancel = $db->query('SELECT * FROM pm_booking_cancel WHERE id_booking = ' . $row['id']);
                        if ($result_cancel !== false && $db->last_row_count() > 0) { ?>
                            <h2>Cancel Information</h2>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <?php foreach ($result_cancel as $cancel) { ?>
                                    <div class="booking_cancel">
                                        <p style="text-transform: capitalize; ">Cancel Mode : <strong><?php echo $cancel['cancel_type']; ?></strong></p>
                                        <p>Cancel Reason : <strong><?php echo $cancel['reason']; ?></strong></p>
                                        <p>Cancellation Charge :<strong><?php echo formatPrice($cancel['refund_charge'] * CURRENCY_RATE);; ?></strong> </p>
                                        <p>Refund amount : <strong><?php echo formatPrice($cancel['refund_amount'] * CURRENCY_RATE);; ?></strong></p>
                                        <p>Cancelled Date : <strong><?php echo date('D, jS F Y', strtotime($cancel['added_date'])); ?></strong></p>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php  }


                        if ($row['status'] == 4) {
                            if (!empty($row['down_payment'])) echo $texts['DOWN_PAYMENT'] . ' : ' . formatPrice($row['down_payment'] * CURRENCY_RATE) . '<br>';
                            echo '<b>' . $texts['BALANCE'] . ' : ' . formatPrice(($row['total'] - $row['down_payment']) * CURRENCY_RATE) . '</b>';
                        } else if ($row['status'] == 2) {
                        } else {
                            echo '<b>' . $texts['BALANCE'] . ' : ' . formatPrice($row['total'] * CURRENCY_RATE) . '</b><br>';
                        }
                        echo '</p>';
                        ?>
                    </div>
                <?php } ?>
            </div>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            <script>
                var checkedIndhandler = function() {
                    $('#stat_checked').html('');
                    $.ajax({
                        type: "get",
                        url: "checked_in_ajax_action.php",
                        data: {
                            "booking_id": <?php echo $id_booking; ?>
                        },
                        cache: false,
                        success: function(data) {
                            if (data == 1) {
                                $('#checked_in').hide();
                                if ($('#ifPaid').val() != '') {
                                    $('#checked_out').show();
                                }
                                $('#stat_checked').html('<span class="label label-default">Checked In</span>');
                                //swal("Booking #<?php echo $id_booking; ?> has Successfully checked in", html, "success");
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                            //$('#stat_checked').html(data);
                        }
                    });

                }
                var checkedOuthandler = function() {
                    $('#stat_checked').html('');
                    $.ajax({
                        type: "get",
                        url: "checked_out_ajax_action.php",
                        data: {
                            "booking_id": <?php echo $id_booking; ?>
                        },
                        cache: false,
                        success: function(data) {
                            if (data == 1) {
                                $('#checked_out').hide();
                                $('#stat_checked').html('<span class="label label-success">Checked out</span>');
                                //swal("Booking #<?php echo $id_booking; ?> has Successfully checked out", html, "success");
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }

                            //$('#msg').html(data);
                        }
                    });
                }
            </script>
        </body>

<?php
    }
} ?>

</html>