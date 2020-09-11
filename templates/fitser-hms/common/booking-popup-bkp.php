<?php
require_once('../../../common/lib.php');
require_once('../../../common/define.php');

if(isset($_POST['id']) && isset($_SESSION['user']['id'])){
    $id_booking = (int)$_POST['id'];
    if(is_numeric($id_booking)){ ?>
        <script>
            function printElem(elem){
                var popup = window.open('', 'print', 'height=800,width=600');
                popup.document.write('<html><head><title>'+document.title+'</title><link rel="stylesheet" href="<?php echo getFromTemplate('css/print.css'); ?>"/></head><body>'+document.getElementById(elem).innerHTML+'</body></html>');
                setTimeout(function(){ 
                    popup.document.close();
                    popup.focus();
                    popup.print();
                    popup.close();    
                }, 600);
                return true;
            }
        </script>
        <div class="white-popup-block">
            <br/>
            <div class="popup_btns">
                <a href="javscript:void(0);" onclick="javascript:printElem('popup-booking-<?php echo $id_booking; ?>');return false;" class="print-btn"><i class="fa fa-print"></i> Print</a>
                <a href="javscript:void(0);" class="print-btn" id="invoice_download"><i class="fa fa-download"></i> Download</a>
                <a href="javscript:void(0);" class="print-btn" id="invoice_email"><i class="fa fa-envelope"></i> Email</a>
            </div>
            <div id="popup-booking-<?php echo $id_booking; ?>" class="popup-booking">
            <?php
            $result_booking = $db->query('SELECT * FROM pm_booking WHERE id = '.$id_booking.' AND id_user = '.$db->quote($_SESSION['user']['id']));
            if($result_booking !== false && $db->last_row_count() > 0){
                
                $row = $result_booking->fetch();
                $result_hotel = $db->query('SELECT * FROM pm_hotel WHERE id = '.$row['id_hotel'])->fetch();
                $result_hotel_file = $db->query('SELECT * FROM pm_hotel_file WHERE id_item = '.$row['id_hotel'].' AND checked = 1 AND lang = '.LANG_ID.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1')->fetch();
                $realpath = SYSBASE.'medias/hotel/small/'.$result_hotel_file['id'].'/'.$result_hotel_file['file'];
                $thumbpath = DOCBASE.'medias/hotel/small/'.$result_hotel_file['id'].'/'.$result_hotel_file['file'];
                $zoompath = DOCBASE.'medias/hotel/big/'.$result_hotel_file['id'].'/'.$result_hotel_file['file'];
            
                echo '
                <h2>'.$texts['BOOKING_SUMMARY'].'</h2>
                
                <div class="booking_details_top">
					<span>Booking #<strong>'.$id_booking.'</strong></span>';
					
	
					switch($row['status']){
                    case 1: echo '<span class="pull-right label label-primary" style="color:#fff">'.$texts['AWAITING'].'</span>'; break;
                    case 2: echo '<span class="pull-right label label-danger" style="color:#fff">'.$texts['CANCELLED'].'</span>'; break;
                    case 3: echo '<span class="pull-right label label-danger" style="color:#fff">'.$texts['REJECTED_PAYMENT'].'</span>'; break;
                    case 4: echo '<span class="pull-right label label-success" style="color:#fff">'.$texts['PAYED'].'</span>'; break;
                    default: echo '<span class="pull-right label label-primary" style="color:#fff">'.$texts['AWAITING'].'</span>'; break;
                }
					
				echo '</div>
				<div class="booking_details_middle">
				<table class="table table-responsive ">
				 <tr>
                        <td><h2>'.$result_hotel['title'].'</h2><p><strong>Hotel Address :</strong> '.$result_hotel['address'].' </p></td>
					
                </tr>
					
				 </table>
				</div>
                
                <table class="table table-responsive table-bordered">
                    <tr>
                        <th width="50%">'.$texts['BOOKING_DETAILS'].'</th>
                        <th width="50%">'.$texts['BILLING_ADDRESS'].'</th>
                    </tr>
                    <tr>
                        <td>
                        
                            '.$texts['CHECK_IN'].' <strong>'.gmstrftime(DATE_FORMAT, $row['from_date']).'</strong><br>
                            '.$texts['CHECK_OUT'].' <strong>'.gmstrftime(DATE_FORMAT, $row['to_date']).'</strong><br>
                            <strong>'.$row['nights'].'</strong> '.$texts['NIGHTS'].'<br>
                            <strong>'.($row['adults']+$row['children']).'</strong> '.$texts['PERSONS'].' - 
                            '.$texts['ADULTS'].': <strong>'.$row['adults'].'</strong> / 
                            '.$texts['CHILDREN'].': <strong>'.$row['children'].'</strong>';
                            if($row['comments'] != '') echo '<p><b>'.$texts['COMMENTS'].'</b><br>'.nl2br($row['comments']).'</p>';
                            echo '
                        </td>
                        <td>
                            '.$row['firstname'].' '.$row['lastname'].'<br>';
                            if($row['company'] != '') echo $texts['COMPANY'].' : '.$row['company'].'<br>';
                            echo nl2br($row['address']).'<br>
                            '.$row['postcode'].' '.$row['city'].'<br>
                            '.$texts['PHONE'].' : '.$row['phone'].'<br>';
                            if($row['mobile'] != '') echo $texts['MOBILE'].' : '.$row['mobile'].'<br>';
                            echo $texts['EMAIL'].' : '.$row['email'].'
                        </td>
                    </tr>
                </table>';
                
                $result_room = $db->query('SELECT * FROM pm_booking_room WHERE id_booking = '.$row['id']);
                if($result_room !== false && $db->last_row_count() > 0){
                    echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>'.$texts['ROOM'].'</th>
                            <th>'.$texts['PERSONS'].'</th>
                            <th class="text-center">'.$texts['TOTAL'].'</th>
                        </tr>';
                        foreach($result_room as $room){
                            echo
                            '<tr>
                                <td>'.$room['title'].'</td>
                                <td>
                                    '.($room['adults']+$room['children']).' '.getAltText($texts['PERSON'], $texts['PERSONS'], ($room['adults']+$room['children'])).': ';
                                    if($room['adults'] > 0) echo $room['adults'].' '.getAltText($texts['ADULT'], $texts['ADULTS'], $room['adults']).' ';
                                    if($room['children'] > 0) echo $room['children'].' '.getAltText($texts['CHILD'], $texts['CHILDREN'], $room['children']).' ';
                                    echo '
                                </td>
                                <td class="text-right" width="15%">'.formatPrice($room['amount']*CURRENCY_RATE).'</td>
                            </tr>';
                        }
                        echo '
                    </table>';
                }
                
                $result_service = $db->query('SELECT * FROM pm_booking_service WHERE id_booking = '.$row['id']);
                if($result_service !== false && $db->last_row_count() > 0){
                    echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>'.$texts['SERVICE'].'</th>
                            <th>'.$texts['QUANTITY'].'</th>
                            <th class="text-center">'.$texts['TOTAL'].'</th>
                        </tr>';
                        foreach($result_service as $service){
                            echo
                            '<tr>
                                <td>'.$service['title'].'</td>
                                <td>'.$service['qty'].'</td>
                                <td class="text-right" width="15%">'.formatPrice($service['amount']*CURRENCY_RATE).'</td>
                            </tr>';
                        }
                        echo '
                    </table>';
                }
                
                $result_activity = $db->query('SELECT * FROM pm_booking_activity WHERE id_booking = '.$row['id']);
                if($result_activity !== false && $db->last_row_count() > 0){
                    echo '
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <th>'.$texts['ACTIVITY'].'</th>
                            <th>'.$texts['DURATION'].'</th>
                            <th>'.$texts['DATE'].'</th>
                            <th>'.$texts['PERSONS'].'</th>
                            <th class="text-center">'.$texts['TOTAL'].'</th>
                        </tr>';
                        foreach($result_activity as $activity){
                            echo
                            '<tr>
                                <td>'.$activity['title'].'</td>
                                <td>'.$activity['duration'].'</td>
                                <td>'.gmstrftime(DATE_FORMAT.' '.TIME_FORMAT, $activity['date']).'</td>
                                <td>
                                    '.($activity['adults']+$activity['children']).' '.getAltText($texts['PERSON'], $texts['PERSONS'], ($activity['adults']+$activity['children'])).': ';
                                    if($activity['adults'] > 0) echo $activity['adults'].' '.getAltText($texts['ADULT'], $texts['ADULTS'], $activity['adults']).' ';
                                    if($activity['children'] > 0) echo $activity['children'].' '.getAltText($texts['CHILD'], $texts['CHILDREN'], $activity['children']).' ';
                                    echo '
                                </td>
                                <td class="text-right" width="15%">'.formatPrice($activity['amount']*CURRENCY_RATE).'</td>
                            </tr>';
                        }
                        echo '
                    </table>';
                }
                echo '
                <table class="table table-responsive table-bordered">';
               
                    if(ENABLE_TOURIST_TAX == 1 && $row['tourist_tax'] > 0){
                        echo '
                        <tr>
                            <th class="text-right">'.$texts['TOURIST_TAX'].'</th>
                            <td class="text-right">'.formatPrice($row['tourist_tax']*CURRENCY_RATE).'</td>
                        </tr>';
                    }
                    
                    if(isset($row['discount']) && $row['discount'] > 0){
                        echo '
                        <tr>
                            <th class="text-right">'.$texts['DISCOUNT'].'</th>
                            <td class="text-right">- '.formatPrice($row['discount']*CURRENCY_RATE).'</td>
                        </tr>';
                    }
         
                    $result_tax = $db->query('SELECT * FROM pm_booking_tax WHERE id_booking = '.$row['id']);
                    if($result_tax !== false && $db->last_row_count() > 0){
                        foreach($result_tax as $tax){
                          
                            echo '
                            <tr>
                                <th class="text-right">'.$tax['name'].'</th>
                                <td class="text-right">'.formatPrice($tax['amount']*CURRENCY_RATE).'</td>
                            </tr>';
                        }
                    }
                    
                    echo '
                    <tr>
                        <th class="text-right">'.$texts['TOTAL'].' ('.$texts['INCL_TAX'].')</th>
                        <td class="text-right" width="15%"><b>'.formatPrice($row['total']*CURRENCY_RATE).'</b></td>
                    </tr>';
                    
                    if(ENABLE_DOWN_PAYMENT == 1 && $row['down_payment'] > 0){
                        echo '
                        <tr>
                            <th class="text-right">'.$texts['DOWN_PAYMENT'].' ('.$texts['INCL_TAX'].')</th>
                            <td class="text-right" width="15%"><b>'.formatPrice($row['down_payment']*CURRENCY_RATE).'</b></td>
                        </tr>';
                    }
                    echo '
                </table>';
                    
                echo '<p><strong>'.$texts['PAYMENT'].'</strong><p>';
                
                echo '<p>'.$texts['PAYMENT_METHOD'].' : '.$row['payment_option'].'<br>';
                echo $texts['STATUS'].': ';
                switch($row['status']){
                    case 1: echo '<span class="label label-primary">'.$texts['AWAITING'].'</span>'; break;
                    case 2: echo '<span class="label label-danger">'.$texts['CANCELLED'].'</span>'; break;
                    case 3: echo '<span class="label label-danger">'.$texts['REJECTED_PAYMENT'].'</span>'; break;
                    case 4: echo '<span class="label label-success">'.$texts['PAYED'].'</span>'; break;
                    default: echo '<span class="label label-primary">'.$texts['AWAITING'].'</span>'; break;
                }
                echo '<br>';
                
                $result_payment = $db->query('SELECT * FROM pm_booking_payment WHERE id_booking = '.$row['id']);
				if($result_payment !== false && $db->last_row_count() > 0){
					echo '
					<table class="table table-responsive table-bordered">
						<tr>
							<th>'.$texts['DATE'].'</th>
							<th>'.$texts['PAYMENT_METHOD'].'</th>
							<th class="text-center">'.$texts['AMOUNT'].'</th>
						</tr>';
						foreach($result_payment as $payment){
							echo
							'<tr>
								<td>'.gmstrftime(DATE_FORMAT.' '.TIME_FORMAT, $payment['date']).'</td>
								<td>'.$payment['method'].'</td>
								<td class="text-right" width="15%">'.formatPrice($payment['amount']*CURRENCY_RATE).'</td>
							</tr>';
						}
						echo '
					</table>';
				}
				
                if($row['status'] == 4){
                    echo $texts['PAYMENT_DATE'].' : '.gmstrftime(DATE_FORMAT.' '.TIME_FORMAT, $row['payment_date']).'<br>';
                    if(!empty($row['down_payment'])) echo $texts['DOWN_PAYMENT'].' : '.formatPrice($row['down_payment']*CURRENCY_RATE).'<br>';
                    if(!empty($row['trans'])) echo $texts['NUM_TRANSACTION'].' : '.$row['trans'];
                }
                echo '</p>';
            } ?>
        </div>
        </div>
        <?php
    }
} ?>
<script>
    $('#invoice_download').on('click', function(){
        $.ajax({
            type     : "post",
            url      : "<?php echo getFromTemplate("common/invoice_download.php"); ?>",
            data     : {
                "booking_id":  <?php echo $id_booking; ?> 
            },
            cache    : false,
            beforeSend : function(){
                swal({
title: "Downloading...",
text: "Please wait",
buttons: false,
allowOutsideClick: false
});
            },
            success  : function(data) {
                swal.close()
               console.log(data);
               var link=document.createElement('a');
              //i used the base url to target a file on mi proyect folder
              link.href=window.URL = data;
              //download the name with a different name given in the php method
              link.download=data;
              link.click();
            }
        });
        
    });
     $('#invoice_email').on('click', function(){
        $.ajax({
            type     : "post",
            url      : "<?php echo getFromTemplate("common/invoice_email.php"); ?>",
            data     : {
                "booking_id":  <?php echo $id_booking; ?> 
            },
            cache    : false,
            beforeSend : function(){
                
            },
            success  : function(data) {
                if(data == 1){
                    swal("Email sent!", "Email has been sent.", "success");
                }else{
                    swal("Failed!", "Email not sent.", "error");
                }
             
            }
        });
        
    });
</script>
