<?php
require_once('../../../common/lib.php');
require_once('../../../common/define.php');

if (isset($_POST['id']) && isset($_SESSION['user']['id'])) {
    $id_booking = (int) $_POST['id'];
    if (is_numeric($id_booking)) { ?>
        <script>
            function printElem(elem) {
                var popup = window.open('', 'print', 'height=800,width=600');
                popup.document.write('<html><head><title>' + document.title + '</title><link rel="stylesheet" href="<?php echo getFromTemplate('css/print.css'); ?>"/></head><body>' + document.getElementById(elem).innerHTML + '</body></html>');
                setTimeout(function() {
                    popup.document.close();
                    popup.focus();
                    popup.print();
                    //popup.close();    
                }, 600);
                return true;
            }
        </script>

        <div class="white-popup-block booking_list_new">
            <div class="container">
                <?php
                $result_booking = $db->query('SELECT * FROM pm_booking WHERE id = ' . $id_booking . ' AND id_user = ' . $db->quote($_SESSION['user']['id']));
                if ($result_booking !== false && $db->last_row_count() > 0) {

                    $row = $result_booking->fetch();
                    $result_hotel = $db->query('SELECT * FROM pm_hotel WHERE id = ' . $row['id_hotel'])->fetch();
                    $result_hotel_file = $db->query('SELECT * FROM pm_hotel_file WHERE id_item = ' . $row['id_hotel'] . ' AND checked = 1 AND lang = ' . LANG_ID . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1')->fetch();
                    $realpath = SYSBASE . 'medias/hotel/small/' . $result_hotel_file['id'] . '/' . $result_hotel_file['file'];
                    $thumbpath = DOCBASE . 'medias/hotel/small/' . $result_hotel_file['id'] . '/' . $result_hotel_file['file'];
                    $zoompath = DOCBASE . 'medias/hotel/big/' . $result_hotel_file['id'] . '/' . $result_hotel_file['file'];
                ?>
                    <div class="booking_details_top">
                        <div class="toppart">
                            <span class="tp2">
                                <!--<a href="javascript:void(0);" onclick="javascript:printElem('popup-booking-<?php echo $id_booking; ?>');return false;"><i class="fas fa-print" aria-hidden="true"></i> Print</a>-->
                                <a href="javascript:void(0);" id="invoice_download"><i class="fas fa-download" aria-hidden="true"></i> Download</a>
                                <a href="javascript:void(0);" id="invoice_email"><i class="fas fa-envelope" aria-hidden="true"></i> Email</a>
                            </span>
                        </div>
                        <h2><?php echo $result_hotel['title']; ?></h2>
                        <h3>Booking ID : <?php echo $id_booking; ?></h3>
                        <h3>Booking Date : <?php echo date('D, jS F Y', $row['add_date']); ?></h3>
                    </div>
                    <div id="popup-booking-<?php echo $id_booking; ?>" class="booking_details_popup">
                        <h2>Hotel Information</h2>
                        <div class="booking_box_top bk_dtl_box noborder">
                            <?php
                            $result_room = $db->query('SELECT COUNT(id) AS tot_room FROM pm_booking_room WHERE id_booking = ' . $row['id']);
                            if ($result_room !== false && $db->last_row_count() > 0) {
                                $tot_room = $result_room->fetch();
                                $tot_room_count =  $tot_room['tot_room'];
                            }
                            ?>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <h3><?php echo $result_hotel['title']; ?></h3>
                                    <p><?php echo $result_hotel['address']; ?></p>
                                    <!--<p>Booking ID : <?php echo $id_booking; ?></p>-->
                                    <p style="text-transform: capitalize;">
                                        <?php
                                        $result_room = $db->query('SELECT * FROM pm_booking_room WHERE id_booking = ' . $row['id']);
                                        if ($result_room !== false && $db->last_row_count() > 0) {

                                            foreach ($result_room as $id_room => $room) {
                                                //       echo '<pre>';
                                                //  print_r($room);
                                                $room_expl = explode('-', $room['title']);
                                                echo $room_expl[1] . ' - ' . ($room['adults'] + $room['children']) . ' ' . getAltText($texts['PERSON'], $texts['PERSONS'], ($room['adults'] + $room['children'])) . ': ';
                                                if ($room['adults'] > 0) echo $room['adults'] . ' ' . getAltText($texts['ADULT'], $texts['ADULTS'], $room['adults']);
                                                if ($room['children'] > 0) echo ' , ';
                                                if ($room['children'] > 0) echo $room['children'] . ' ' . getAltText($texts['CHILD'], $texts['CHILDREN'], $room['children']);
                                        ?>
                                                <br>
                                        <?php }
                                        } ?>
                                    </p>
                                    <?php
                                    $result_offer = $db->query('SELECT * FROM pm_booking_offer WHERE id_booking = ' . $row['id']);
                                    if ($result_offer !== false && $db->last_row_count() > 0) {
                                        //$offer = $result_offer->fetch(); 
                                        $offer = $result_offer->fetch(PDO::FETCH_ASSOC); ?>
                                        <p><?php echo $offer['title'] ?> </p>
                                        <div class="capacity">
                                            <?php
                                            $adults = $offer['adults'];
                                            $kids = $offer['children'];
                                            if ($adults > 0) {
                                                echo '<span>' . $adults . '</span>';
                                                echo '<span>' . ($adults > 1 ? ' Adults ' : ' Adult ') . '</span>';
                                            }
                                            if ($kids > 0) {
                                                echo '<span>' . $kids . '</span>';
                                                echo '<span>' . ($kids > 1 ? ' Kids' : ' Kid') . '</span>';
                                            }
                                            ?>
                                        </div>
                                        <div class="duration">
                                            <?php
                                            $nights = $offer['num'];
                                            $days = ($nights + 1);
                                            echo '<span>' . $days . '</span>';
                                            echo '<span>' . ($days > 1 ? ' Days ' : ' Day ') . '</span>';
                                            echo '<span>' . $nights . '</span>';
                                            echo '<span>' . ($nights > 1 ? 'Nights' : 'Night') . '</span>';
                                            ?>
                                        </div>
                                    <?php }
                                    ?>


                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                    <p>Check In</p>
                                    <h3><?php echo date('D, jS F Y', $row['from_date']); ?></h3>
                                </div>
                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                    <p>Check Out</p>
                                    <h3><?php echo date('D, jS F Y', $row['to_date']); ?></h3>
                                </div>
                            </div>
                        </div>
                        <h2>Travellar Information</h2>
                        <div class="booking_box_top noborder">
                            <div class="row">
                                <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
                                    <span class="fontawsome"><i style="font-size:13px" class="fa fa-users" aria-hidden="true"></i></span>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <h3><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></h3>
                                    <p><?php echo $row['email']; ?></p>
                                </div>
                                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                                    <p><?php echo $row['mobile']; ?></p>
                                </div>
                            </div>
                        </div>
                        <h2>Payment Information</h2>
                        <div class="booking_box_top noborder">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <?php $result_cancel = $db->query('SELECT * FROM pm_booking_cancel WHERE id_booking = ' . $row['id']);
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
                                                    <p>Cancelled : <strong><?php echo $cancel['cancel_element']; ?></strong></p>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php  } ?>
                                    <?php
                                    if ($row['amount'] != $row['down_payment']) {
                                        if ($row['down_payment'] == '' || $row['down_payment'] == NULL) {
                                            $paidAmnt = 0;
                                        } else {
                                            $paidAmnt = $row['down_payment'];
                                        }
                                        $due =  $row['amount'] - $paidAmnt;
                                    ?>
                                        <p class="edtpara">Due Amount : <span><?php echo formatPrice($due * CURRENCY_RATE); ?></span></p>
                                    <?php } else if ($row['amount'] == $row['down_payment']) { ?>
                                        <p class="edtpara">Paid Amount : <span><?php echo formatPrice($row['amount'] * CURRENCY_RATE); ?></span></p>
                                    <?php } ?>
                                    <?php
                                    $result_payment = $db->query('SELECT * FROM pm_booking_payment WHERE id_booking = ' . $row['id']);
                                    if ($result_payment !== false && $db->last_row_count() > 0) {
                                        foreach ($result_payment as $payment) {
                                    ?>
                                            <p>Payment Mode : <?php echo $payment['method']; ?></p>
                                    <?php
                                        }
                                    } ?>
                                </div>
                            </div>

                        </div>
                    <?php } ?>
                    </div>
            </div>
        </div>
<?php
    }
} ?>
<script>
    $('#invoice_download').on('click', function() {
        $.ajax({
            type: "post",
            url: "<?php echo getFromTemplate("common/invoice_download.php"); ?>",
            data: {
                "booking_id": <?php echo $id_booking; ?>
            },
            cache: false,
            beforeSend: function() {
                swal({
                    title: "Downloading...",
                    text: "Please wait",
                    buttons: false,
                    allowOutsideClick: false
                });
            },
            success: function(data) {
                swal.close()
                console.log(data);
                var link = document.createElement('a');
                //i used the base url to target a file on mi proyect folder
                link.href = window.URL = data;
                //download the name with a different name given in the php method
                link.download = data;
                link.click();
            }
        });

    });
    $('#invoice_email').on('click', function() {
        $.ajax({
            type: "post",
            url: "<?php echo getFromTemplate("common/invoice_email.php"); ?>",
            data: {
                "booking_id": <?php echo $id_booking; ?>
            },
            cache: false,
            beforeSend: function() {

            },
            success: function(data) {
                if (data == 1) {
                    swal("Email sent!", "Email has been sent.", "success");
                } else {
                    swal("Failed!", "Email not sent.", "error");
                }

            }
        });

    });
</script>