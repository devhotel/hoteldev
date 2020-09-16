<?php
require_once('../../../common/lib.php');
require_once('../../../common/define.php');
if (isset($_POST['id']) && isset($_SESSION['user']['id'])) {
  $id_booking = (int) $_POST['id'];
  if (is_numeric($id_booking)) {
    $booking = $db->query("SELECT * FROM pm_booking AS pbm WHERE pbm.`id` = " . $id_booking)->fetch();
    $booking_detail = $db->query("SELECT * FROM pm_booking_room AS pbm WHERE pbm.`id_booking` = " . $id_booking)->fetch();
    $room_cancel_policy = $db->query("SELECT * FROM `pm_hotel_cancel_policy`  WHERE `id_hotel` = " . $booking['id_hotel'])->fetchAll();
    $checkin = $booking['from_date'];
    $checkout = $booking['to_date'];
    $nights = $booking['nights'];
    $cin = date("d/m/Y", $checkin);
    $maxin = strtotime(" +1 day", $checkin);
    $maxin = date("d/m/Y", $maxin);
    $maxout = strtotime(" -1 day", $checkout);
    $maxout = date("d/m/Y", $maxout);
    $cout = date("d/m/Y", $checkout);
    $cancelpolicy = array();
    $conditions = array();

    if (!empty($room_cancel_policy)) {
      //var_dump($room_cancel_policy);

      foreach ($room_cancel_policy as $key => $val) {
        $duration = 0;
        if ($val['duration_type'] == 'day') {
          $duration = $val['value'] * 24;
          $conditions[$duration][] = $val;
        } else {
          $duration = $val['value'];
          $conditions[$duration][] = $val;
        }
      }
      ksort($conditions);
      foreach ($conditions as $key => $condition) {
        $bfore = '- ' . $key . ' hours';
        $checkin_before = strtotime($bfore, $checkin);
        $today = time();
        if (($today >= $checkin_before) && ($today <= $checkin)) {
          $cancelpolicy = $condition;
          break;
        }
      }
    } else {
      $cancelpolicy[0]['fees'] = 100;
      $cancelpolicy[0]['fees_type'] = 'parcentage';
      $cancelpolicy[0]['value'] = 0;
      $cancelpolicy[0]['duration_type'] = 'day';
    }
    if (empty($cancelpolicy)) {
      if (!empty($conditions)) {
        $cancelpolicy[0]['fees'] = 100;
        $cancelpolicy[0]['fees_type'] = 'parcentage';
        $cancelpolicy[0]['value'] = 0;
        $cancelpolicy[0]['duration_type'] = 'day';
      } else {
        $cancelpolicy[0]['fees'] = 100;
        $cancelpolicy[0]['fees_type'] = 'parcentage';
        $cancelpolicy[0]['value'] = 0;
        $cancelpolicy[0]['duration_type'] = 'day';
      }
    }
    //var_dump($cancelpolicy);
    $cancel_fees = $cancelpolicy[0]['fees'];
    $fees_type = $cancelpolicy[0]['fees_type'];
    $duration = $cancelpolicy[0]['value'];
    $duration_type = $cancelpolicy[0]['duration_type'];
    $number_of_days = '';
    if ($duration_type == 'day') {
      $number_of_days = $duration . ' Days';
    } else {
      $number_of_days = $duration . ' Hours';
    }
    if ($fees_type == 'parcentage') {
      $txt_cncl = $cancel_fees . '%';
    } else {
      $txt_cncl = formatPrice($cancel_fees * CURRENCY_RATE);
    }
    /* New Cancel Amount Checking Start*/
    $cancel_fees = '';
    $fees_type = '';
    $bookingDates = $fromDAte = $db->query('SELECT total, FROM_UNIXTIME(from_date,"%Y-%m-%d") as checkin_date, FROM_UNIXTIME(to_date,"%Y-%m-%d") as checkout_date FROM pm_booking WHERE id = "' . $id_booking . '"')->fetch(PDO::FETCH_ASSOC);
    $cancelDateDiff = date_diff(date_create($bookingDates['checkin_date'] . ' 12:00:00'), date_create(date('Y-m-d H:i:s')));
    $cancelDateDifferenceHours = ($cancelDateDiff->format("%a") * 24) + $cancelDateDiff->format("%h");
    $booking_days = date_diff(date_create($bookingDates['checkout_date']), date_create($bookingDates['checkin_date']))->format("%a");
    $total_amount = $bookingDates['total'];
    if ($booking['payment_option'] != 'arrival') {
      if ($cancelDateDifferenceHours >= 168) {
        $refundMessage = 'If you cancel booking now, No Cancellation Charge will be deducted';
        $refundCharge = 0.00;
        $refundAmount = $total_amount;
        $cancel_fees = 0;
        $fees_type = 'parcentage';
      } else if ($cancelDateDifferenceHours < 168 && $cancelDateDifferenceHours >= 48) {
        $refundMessage = 'If you cancel booking now, 50% of booking amount as Cancellation Charge will be deducted';
        $refundCharge = ($total_amount * 50) / 100;
        $refundAmount = $total_amount - $refundCharge;
        $cancel_fees = 50;
        $fees_type = 'parcentage';
      } else if ($cancelDateDifferenceHours < 48) {
        $refundMessage = 'If you cancel booking now, 100% of booking amount as Cancellation Charge will be deducted';
        $refundCharge = $total_amount;
        $refundAmount = 0.00;
        $cancel_fees = 100;
        $fees_type = 'parcentage';
      }
    } else {
      $refundMessage = 'If you cancel booking now, No Cancellation Charge will be deducted';
      $refundCharge = $total_amount;
      $refundAmount = 0.00;
      $cancel_fees = 100;
      $fees_type = 'parcentage';
    }
    //If booking cancel now then no cancellation charge will be deducted
    // $cancelpolicy['refundMessage'] = $refundMessage;
    // $cancelpolicy['refundCharge'] = $refundCharge;
    // $cancelpolicy['refundAmount'] = $refundAmount;
    // print_r($cancelpolicy);
    // die;
    /* New Cancel Amount Checking End*/
?>
    <div class="white-popup-block cancel_booking_popup" id="popup-booking-<?php echo $id_booking; ?>">
      <div class="white-popup-cancel">
        <div class="cncl_modalbody_top">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="cnclbox">
                <h2><i class="fa fa-exclamation-circle" aria-hidden="true"></i>Cancellation Fees</h2>
                <?php //if ($duration > 0) { 
                ?>
                <!-- <p>If booking cancelled within <strong><?php echo $number_of_days; ?> </strong> of the scheduled Check-in then cancellation charges will be deducted at <strong><?php echo $txt_cncl; ?></strong>.</p> -->
                <?php //} else { 
                ?>
                <!-- <p>If booking cancel now then no cancellation charge will be deducted</p> -->
                <?php //} 
                ?>
                <?= $refundMessage ?>
              </div>
            </div>
          </div>
        </div>
        <div class="cncl_modalbody_bottom">
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">
              <h2>Please select a Date range to Cancel</h2>
              <div class="cnclbox">
                <div class="input-wrapper datepicker-wrapper form-inline">
                  <i class="fas fa-fw fa-calendar hidden-xs"></i>
                  <div class="input-group from-date">
                    <input type="text" class="form-control text-right" id="from_picker" name="from_date" value="<?php echo $cin; ?>" placeholder="<?php echo $texts['CHECK_IN']; ?>" autocomplete="off">
                  </div>
                  <i class="fas fa-fw fa-long-arrow-alt-right"></i>
                  <div class="input-group to-date">
                    <input type="text" class="form-control" id="to_picker" name="to_date" value="<?php echo $cout; ?>" placeholder="<?php echo $texts['CHECK_OUT']; ?>" autocomplete="off">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
              <h2>Please select No. of Rooms</h2>
              <div id="checkboxSelectCombo"></div>
              <div class="cnclbox2">
                <?php
                $booking_detail = $db->query("SELECT *  FROM pm_booking_room AS pbm WHERE chk=1 AND  `id_booking` = " . $id_booking)->fetchAll();
                foreach ($booking_detail as $key => $value) { ?>
                  <p><input type="checkbox" name="rooms[]" class="select_croom" value="<?php echo $value['id'] ?>" checked="checked"><span><?php echo $value['title']; ?></span></p>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
          <input type="hidden" name="reason" id="reason" value="">
          <input type="hidden" name="cancel_fees" id="cancel_fees" value="<?= $cancel_fees ?>">
          <input type="hidden" name="fees_type" id="fees_type" value="<?= $fees_type ?>">
          <input type="hidden" name="refundCharge" id="refundCharge" value="<?= $refundCharge ?>">
          <input type="hidden" name="refundAmount" id="refundAmount" value="<?= $refundAmount ?>">
          <h2>Please select a reason for cancellation</h2>
          <a href="javascript:void(0);" class="reason_cls" data-val="Change plan"><i class="fa fa-retweet" aria-hidden="true"></i> Change plan</a>
          <a href="javascript:void(0);" class="reason_cls" data-val="Got a better deal"><i class="fa fa-object-ungroup" aria-hidden="true"></i> Got a better deal</a>
          <a href="javascript:void(0);" class="reason_cls" data-val="Want to book different hotel"><i class="fa fa-bed" aria-hidden="true"></i> Want to book different hotel</a>
          <a href="javascript:void(0);" class="reason_cls" data-val="Booking created by mistake"><i class="fa fa-random" aria-hidden="true"></i> Booking created by mistake</a>
          <a href="javascript:void(0);" class="reason_cls" data-val="other"><i class="fa fa-random" aria-hidden="true"></i> OTHERS </a>
          <div id="other_box" style="display: none;" class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <!-- <lebel>Enter Other reason for cancellation</lebel> -->
            <input type="text" name="other_reason" id="other_reason" class="form-control" placeholder="Enter Other reason for cancellation" value="">
          </div>
        </div>
        <span class="loader" style="display: none;"><i class="fas fa-spinner fa-pulse"></i></span>
        <div class="col-md-12"><span id="msg"></span></div>
        <div class="confirm_btn"><button type="button" id="cancel_btn" class="btn btn-danger" width="100%">Confirm</button></div>
      </div>
    </div>
  <?php } ?>
<?php } ?>
<!-- Ignite UI Required Combined JavaScript Files -->
<script type="text/javascript">
  // date range
  if ($('#from_picker').length && $('#to_picker').length) {
    $('#from_picker').datepicker({
      dateFormat: 'dd/mm/yy',
      minDate: '<?= $cin ?>',
      maxDate: '<?= $maxout ?>',
      onClose: function(selectedDate) {
        var a = selectedDate.split('/');
        var d = new Date(a[2] + '/' + a[1] + '/' + a[0]);
        var t = new Date(d.getTime() + 86400000);
        var date = t.getDate() + '/' + (t.getMonth() + 1) + '/' + t.getFullYear();
        //$('#to_picker').datepicker('setDate', date);
        $('#to_picker').datepicker('option', 'minDate', date);
      }
    });
    $('#to_picker').datepicker({
      dateFormat: 'dd/mm/yy',
      minDate: '<?= $maxin ?>',
      maxDate: '<?= $cout ?>',

    });
  }

  $(".reason_cls").on('click', function() {
    $('#msg').html('');
    $('a').removeClass('block-active');
    var data_val = $(this).attr('data-val');
    if (data_val != "") {
      if (data_val != 'other') {
        $("#reason").val(data_val);
        $("#other_box").hide();
      } else {
        $("#reason").val('');
        $("#other_box").show();
      }
      $(this).addClass('block-active');
    }
  });

  $("#other_reason").on('keyup', function() {
    $('#msg').html('');
    $("#reason").val($(this).val());
  });

  $('#cancel_btn').on('click', function() {
    $('#msg').html('');
    var atLeastOneIsChecked = false;
    var chkrooms = [];
    i = 0;
    $('input:checkbox').each(function() {
      if ($(this).is(':checked')) {
        atLeastOneIsChecked = true;
        //return false;
        chkrooms[i++] = $(this).val();
      }

    });
    //console.log(chkrooms);
    var reason = $("#reason").val();
    var from_date = $("#from_picker").val();
    var to_date = $("#to_picker").val();
    var cancel_fees = $("#cancel_fees").val();
    var fees_type = $("#fees_type").val();
    //var no_room = $("#no_room").val();
    var no_room = chkrooms;
    if (reason != "" && atLeastOneIsChecked == true) {
      $.ajax({
        type: "post",
        url: "<?php echo getFromTemplate("common/cancel_ajax.php"); ?>",
        data: {
          "reason": reason,
          "from_date": from_date,
          "to_date": to_date,
          "cancel_fees": cancel_fees,
          "fees_type": fees_type,
          "no_room": no_room,
          "refundCharge": $("#refundCharge").val(),
          "refundAmount": $("#refundAmount").val(),
          "booking_id": <?php echo $id_booking; ?>
        },
        cache: false,
        beforeSend: function() {
          $('.loader').show();
        },
        success: function(data) {
          $('.loader').hide();
          $('#cancel_btn').hide();
          $('#msg').html(data);
          setTimeout(function() {
            location.reload();
          }, 2000);
        }
      });
    } else {
      if (reason == "") {
        var msg = '<div class="alert alert-danger">Please select a reason for cancellation</div>';
      }
      if (atLeastOneIsChecked == false) {
        var msg = '<div class="alert alert-danger">Please Check at least one room for cancellation</div>';
      }
      $('#msg').html(msg);
      //alert("Please select a reason for cancellation");
    }
  });
</script>