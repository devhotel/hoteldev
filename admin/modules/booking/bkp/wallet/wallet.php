<?php 
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');
if(isset($_POST['id'])){
    $id = (int)$_POST['id'];
    $wallet_info = $db->query("SELECT * FROM pm_wallet WHERE id=".$db->quote($id));
    $wallet_result = $wallet_info->fetch();
    $user_id=$wallet_result['user_id'];
    $amount = $wallet_result['amount']; 
    $wallet_historys = $db->query("SELECT * FROM pm_wallet_history WHERE user_id=".$db->quote($user_id)." ORDER BY id DESC");


  ?>
<div class="white-popup-block" id="popup-booking-<?php echo $id_booking; ?>">
    <style>.cncl_modalbody_top {width:100%;background: #eee;padding: 10px;}
.cnclbox {width: 100%;}
.cnclbox h2 {font-size: 14px;color: #535352;font-weight: 600;margin: 0 0 5px;}
.cnclbox p {font-size: 12px;color: #535352;font-weight: 400;margin: 0;line-height: 13px;}
.cnclbox2 {width: 100%;text-align: center;font-size: 15px;color: #6ebb12;font-weight: 600;border-left:1px solid #ccc;}
.cnclbox2 b {font-weight: 400;font-size: 12px;}
.cncl_modalbody_bottom {width: 100%;margin-top: 15px;border-top:1px solid #ccc;padding: 10px 0 0;display: inline-block;}
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
<link rel="stylesheet" href="<?php echo DOCBASE; ?>common/css/table-fixed-header.css">
 <div class="cncl_modalbody_top">
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="cnclbox">
               <h2><span class="icon icon-wallet-black" style="width:16px;height:16px;"></span>Wallet : <?php echo formatPrice($amount*CURRENCY_RATE); ?></h2>
            </div>
          </div>
        </div>
      </div>
      <div class="cncl_modalbody_bottom">
        <div class="sticky-table sticky-headers sticky-rtl-cells">
              <table class="table table-bordered id_table" id="listing_base">
                              <thead class='header'>
                                <tr class="sticky-row">
                                  <th scope="col">#</th>
                                  <th scope="col">Date</th>
                                  <th scope="col">Remarks</th>
                                  <th scope="col">Amount</th>
                                </tr>
                              </thead>
                              <?php
                              if(!empty($wallet_historys)){
                              ?>
                              <tbody>
                                  <?php
                                  foreach($wallet_historys as $key=>$wh){
                                  ?>
                                <tr class="table-success">
                                  <td>#<?php echo ++$key; ?></td>
                                  <td><?php echo gmstrftime(DATE_FORMAT, $wh['c_date']); ?></td>
                                  <td>
                                      <?php
                                      switch($wh['purpose']){
                                          case 'cancel':
                                              echo 'Cancel Booking';
                                            break;
                                          case 'booking':
                                              echo 'New Booking';
                                            break; 
                                      }
                                      ?>
                                  </td>
                                  <td><span style="<?php echo ($wh['type'] == 'credit') ? 'color:#008000' : 'color:#b70000'; ?>"><?php echo formatPrice($wh['amount']*CURRENCY_RATE); ?></span> (<?php echo $wh['type']; ?>)</td>
                                </tr>
                                <?php } ?>
                              </tbody>
                              <?php }else{ ?>
                              <p class="lead text-center text-muted">You have no balance in wallet.</p>
                              <?php } ?>
                            </table>
        </div>
      </div>
      <span class="loader" style="display: none;"><i class="fas fa-spinner fa-pulse"></i></span>
      <span id="msg"></span>
      <!--<button type="button" id="cancel_btn" class="btn btn-danger" width="100%">Confirm</button>-->
    </div>
    <?php ?>
<?php } ?>


<script src="<?php echo DOCBASE; ?>common/js/table-fixed-header.js"></script>
<script language='javascript' type='text/javascript'>
      $(document).ready(function(){
      $('.table-fixed-header').fixedHeader();
      });
    </script>
<script type="text/javascript">
  $(".reason_cls").on('click', function(){
    $('a').removeClass('block-active');
    var data_val = $(this).attr('data-val');
    if(data_val !=""){
        $("#reason").val(data_val);
        $(this).addClass('block-active');
    }
  });
    $('#cancel_btn').on('click', function(){
    var reason = $("#reason").val();
    if(reason != ""){
        $.ajax({
          type     : "post",
          url      : "<?php echo getFromTemplate("common/cancel_ajax.php"); ?>",
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
      alert("Please select a reson for cancellation");
    }
  });
</script>