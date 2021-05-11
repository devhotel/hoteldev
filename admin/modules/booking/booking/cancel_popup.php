<?php 
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');
if(isset($_POST['id']) && isset($_SESSION['admin']['id'])){
    $id_booking = (int)$_POST['id'];
    if(is_numeric($id_booking)){ 
    $booking_detail = $db->query("SELECT * FROM pm_booking_room AS pbm WHERE pbm.`id_booking` = ".$id_booking)->fetch();
    $room_detail = $db->query("SELECT * FROM pm_room AS pr WHERE pr.`id` = ".$booking_detail['id_room'])->fetch();
    //print_r($booking_detail);
    $cancel_fees = $room_detail['cancel_fees'];
    $fees_type = $room_detail['fees_type'];
    if($fees_type == 'parcentage'){
        $txt_cncl = $cancel_fees.'%';
    }else{
        $txt_cncl = formatPrice($cancel_fees*CURRENCY_RATE);
    }


  ?>
<div class="white-popup-block" id="popup-booking-<?php echo $id_booking; ?>">
  <div class="white-popup-block-inner" >
    <style>.cncl_modalbody_top {width:100%;background: #eee;padding: 10px;}
.cnclbox {width: 100%;}
.cnclbox h2 {font-size: 14px;color: #535352;font-weight: 600;margin: 0 0 5px;}
.cnclbox p {font-size: 12px;color: #535352;font-weight: 400;margin: 0;line-height: 13px;}
.cnclbox2 {width: 100%;text-align: center;font-size: 15px;color: #6ebb12;font-weight: 600;border-left:1px solid #ccc;}
.cnclbox2 b {font-weight: 400;font-size: 12px;}
.cncl_modalbody_bottom {width: 100%;margin-top: 15px;padding: 10px 0 0;display: inline-block;}
.cncl_modalbody_bottom h2 {margin: 0 0 30px;text-align: center;font-size: 14px;color: #535352;font-weight: 600;}
.cncl_modalbody_bottom a {width:46%;float:left;border:1px solid #ccc;padding: 20px;margin: 0 10px 10px;font-size: 13px;color: #000;font-weight: 400;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;text-align: center;}
.block-active{background: #d6ad39;}
#cancel_btn{width:100%;}
span.loader {
    text-align: center;
    font-size: 60px;
    color: #000;
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background: rgba(255,255,255,0.6);
    vertical-align: middle;
}
.loader svg{
    margin: 160px;
}</style>
 <!--<div class="cncl_modalbody_top">-->
 <!--       <div class="row">-->
 <!--         <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">-->
 <!--           <div class="cnclbox">-->
 <!--             <h2><i class="fa fa-exclamation-circle" aria-hidden="true"></i>Cancellation Fees</h2>-->
 <!--             <p>If booking cancelled within <?php echo $room_detail['number_of_days_full']; ?> days of the scheduled departure then cancellation charges will be deducted at <?php echo $txt_cncl; ?>.</p>-->
 <!--           </div>-->
 <!--         </div>-->
 <!--         <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">-->
 <!--           <div class="cnclbox2">-->
 <!--             100% <br><b>Refundable </b>-->
 <!--             <p>Free cancellation within <?php echo $room_detail['number_of_days_full']; ?> days before the scheduled departure.</p>-->
 <!--           </div>-->
 <!--         </div>-->
 <!--       </div>-->
 <!--     </div>-->
      <div class="cncl_modalbody_bottom">
        <input type="hidden" name="reason" id="reason" value="">
        <h2>Please select a reason for cancellation</h2>
        <a href="javascript:void(0);" class="reason_cls" data-val="Change plan"><i class="fa fa-retweet" aria-hidden="true"></i> Change plan</a>
        <a href="javascript:void(0);" class="reason_cls" data-val="Got a better deal"><i class="fa fa-object-ungroup" aria-hidden="true"></i> Got a better deal</a>
        <a href="javascript:void(0);" class="reason_cls" data-val="Want to book different hotel"><i class="fa fa-bed" aria-hidden="true"></i> Want to book different hotel</a>
        <a href="javascript:void(0);" class="reason_cls" data-val="Booking created by mistake"><i class="fa fa-random" aria-hidden="true"></i> Booking created by mistake</a>
        
        <a href="javascript:void(0);" class="reason_cls" data-val="other"><i class="fa fa-random" aria-hidden="true"></i>Others</a>
         <div id="other_box" style="display: none;" class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
             <lebel>Enter Other reason for cancellation</lebel>
             <input type="text" name="other_reason" id="other_reason" class="form-control" value="" >
         </div> 
      </div>
      <span class="loader" style="display: none;"><i class="fas fa-spinner fa-pulse"></i></span>
       <div class="col-md-12"><span id="msg"></span></div>
      <button type="button" id="cancel_btn" class="btn btn-danger" width="100%">Confirm</button>
    </div>
  </div>
    <?php } ?>
<?php } ?>
<script type="text/javascript">
      $(".reason_cls").on('click', function(){
        $('#msg').html('');
        $('a').removeClass('block-active');
        var data_val = $(this).attr('data-val');
        if(data_val !=""){
            if(data_val!='other'){
                $("#reason").val(data_val);
                $("#other_box").hide();
            }else{
                $("#reason").val('');
                $("#other_box").show();
            }
            $(this).addClass('block-active');
        }
      });
   
    $("#other_reason").on('keyup', function(){
       $('#msg').html('');
       $("#reason").val($(this).val());
    });
    
     $('#cancel_btn').on('click', function(){
      $('#msg').html('');
       var reason = $("#reason").val();
       if(reason != ""){
            $.ajax({
                  type     : "post",
                  url      : "<?php echo getFromTemplate("common/cancel_admin_ajax.php"); ?>",
                  data     : {
                      "reason": reason,
                      "booking_id":  <?php echo $id_booking; ?> 
                  },
                  cache    : false,
                  beforeSend : function(){
                     $('.loader').show();   
                  },
                  success  : function(data) {
                      $('.loader').hide(); 
                      $('#cancel_btn').hide();
                       $('#msg').html(data);
                       setTimeout(function(){
                          location.reload();
                       }, 2000);
                  }
          });
    }else{
        var msg='<div class="alert alert-danger">Please select a reason for cancellation</div>';
        $('#msg').html(msg);
      //alert("Please select a reson for cancellation");
    }
  });
</script>