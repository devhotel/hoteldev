<?php 
require_once('../../common/lib.php');
require_once('../../common/define.php');

$data=json_decode(file_get_contents('php://input'), true);

//$id_booking = $_POST['booking_id'];
$id_booking = $data['booking_id'];
$user_id = $data['user_id'];

if($id_booking > 0){
    $html ='<style>table tr td {
    border: 1px solid #ccc;
}</style>';

            //$result_booking = $db->query('SELECT * FROM pm_booking WHERE id = '.$id_booking.' AND id_user = '.$db->quote($_SESSION['user']['id']));
			$result_booking = $db->query('SELECT * FROM pm_booking WHERE id = '.$id_booking.' AND id_user = '.$user_id);
            if($result_booking !== false && $db->last_row_count() > 0){
                
                $row = $result_booking->fetch();
                $result_hotel = $db->query('SELECT * FROM pm_hotel WHERE id = '.$row['id_hotel'])->fetch();
                $result_hotel_file = $db->query('SELECT * FROM pm_hotel_file WHERE id_item = '.$row['id_hotel'].' AND checked = 1 AND lang = '.LANG_ID.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1')->fetch();
                $realpath = SYSBASE.'medias/hotel/small/'.$result_hotel_file['id'].'/'.$result_hotel_file['file'];
                $thumbpath = DOCBASE.'medias/hotel/small/'.$result_hotel_file['id'].'/'.$result_hotel_file['file'];
                $zoompath = DOCBASE.'medias/hotel/big/'.$result_hotel_file['id'].'/'.$result_hotel_file['file'];
            
                $html .= '
                <h2 style="text-transform: uppercase;font-weight: 600;font-family: "Montserrat"; color: #2f9df2;">'.$texts['BOOKING_SUMMARY'].'</h2>
                
                <div class="booking_details_top" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
					<span>Booking #<strong>'.$id_booking.'</strong></span>';
					
	
					switch($row['status']){
                    case 1: $html .= '<span class="pull-right label label-primary" style="color:#fff">'.$texts['AWAITING'].'</span>'; break;
                    case 2: $html .= '<span class="pull-right label label-danger" style="color:#fff">'.$texts['CANCELLED'].'</span>'; break;
                    case 3: $html .= '<span class="pull-right label label-danger" style="color:#fff">'.$texts['REJECTED_PAYMENT'].'</span>'; break;
                    case 4: $html .= '<span class="pull-right label label-success" style="color:#fff">'.$texts['PAYED'].'</span>'; break;
                    default: $html .= '<span class="pull-right label label-primary" style="color:#fff">'.$texts['AWAITING'].'</span>'; break;
                }
					
				$html .= '</div>
				<div class="booking_details_middle">
				<table class="table table-responsive" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
				 <tr>
                        <td style="padding:15px;"><h2>'.$result_hotel['title'].'</h2><p><strong>Hotel Address :</strong> '.$result_hotel['address'].' </p></td>
					
                </tr>
					
				 </table>
				</div>
                
                <table class="table table-responsive table-bordered" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
                    <tr>
                        <th width="50%" style="text-align: left;text-transform: uppercase;">'.$texts['BOOKING_DETAILS'].'</th>
                        <th width="50%" style="text-align: left;text-transform: uppercase;">'.$texts['BILLING_ADDRESS'].'</th>
                    </tr>
                    <tr>
                        <td style="padding:15px;">
                        
                            '.$texts['CHECK_IN'].' <strong>'.gmstrftime(DATE_FORMAT, $row['from_date']).'</strong><br>
                            '.$texts['CHECK_OUT'].' <strong>'.gmstrftime(DATE_FORMAT, $row['to_date']).'</strong><br>
                            <strong>'.$row['nights'].'</strong> '.$texts['NIGHTS'].'<br>
                            <strong>'.($row['adults']+$row['children']).'</strong> '.$texts['PERSONS'].' - 
                            '.$texts['ADULTS'].': <strong>'.$row['adults'].'</strong> / 
                            '.$texts['CHILDREN'].': <strong>'.$row['children'].'</strong>';
                            if($row['comments'] != '') $html .= '<p><b>'.$texts['COMMENTS'].'</b><br>'.nl2br($row['comments']).'</p>';
                            $html .= '
                        </td>
                        <td style="padding:15px;">
                            '.$row['firstname'].' '.$row['lastname'].'<br>';
                            if($row['company'] != '') $html .= $texts['COMPANY'].' : '.$row['company'].'<br>';
                            $html .= nl2br($row['address']).'<br>
                            '.$row['postcode'].' '.$row['city'].'<br>
                            '.$texts['PHONE'].' : '.$row['phone'].'<br>';
                            if($row['mobile'] != '') $html .= $texts['MOBILE'].' : '.$row['mobile'].'<br>';
                            $html .= $texts['EMAIL'].' : '.$row['email'].'
                        </td>
                    </tr>
                </table>';
                
                $result_room = $db->query('SELECT * FROM pm_booking_room WHERE id_booking = '.$row['id']);
                if($result_room !== false && $db->last_row_count() > 0){
                    $html .= '
                    <table class="table table-responsive table-bordered" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
                        <tr>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['ROOM'].'</th>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['PERSONS'].'</th>
                            <th class="text-center" style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['TOTAL'].'</th>
                        </tr>';
                        foreach($result_room as $room){
                            $html .=
                            '<tr>
                                <td style="padding:15px;">'.$room['title'].'</td>
                                <td style="padding:15px;">
                                    '.($room['adults']+$room['children']).' '.getAltText($texts['PERSON'], $texts['PERSONS'], ($room['adults']+$room['children'])).': ';
                                    if($room['adults'] > 0) $html .= $room['adults'].' '.getAltText($texts['ADULT'], $texts['ADULTS'], $room['adults']).' ';
                                    if($room['children'] > 0) $html .= $room['children'].' '.getAltText($texts['CHILD'], $texts['CHILDREN'], $room['children']).' ';
                                    $html .= '
                                </td>
                                <td class="text-right" width="15%" style="padding:15px;">'.formatPrice($room['amount']*CURRENCY_RATE).'</td>
                            </tr>';
                        }
                        $html .= '
                    </table>';
                }
                
                $result_service = $db->query('SELECT * FROM pm_booking_service WHERE id_booking = '.$row['id']);
                if($result_service !== false && $db->last_row_count() > 0){
                    $html .= '
                    <table class="table table-responsive table-bordered" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
                        <tr>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['SERVICE'].'</th>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['QUANTITY'].'</th>
                            <th class="text-center" style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['TOTAL'].'</th>
                        </tr>';
                        foreach($result_service as $service){
                            $html .=
                            '<tr>
                                <td style="padding:15px;">'.$service['title'].'</td>
                                <td style="padding:15px;">'.$service['qty'].'</td>
                                <td class="text-right" width="15%" style="padding:15px;">'.formatPrice($service['amount']*CURRENCY_RATE).'</td>
                            </tr>';
                        }
                        $html .= '
                    </table>';
                }
                
                $result_activity = $db->query('SELECT * FROM pm_booking_activity WHERE id_booking = '.$row['id']);
                if($result_activity !== false && $db->last_row_count() > 0){
                    $html .= '
                    <table class="table table-responsive table-bordered" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
                        <tr>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['ACTIVITY'].'</th>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['DURATION'].'</th>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['DATE'].'</th>
                            <th style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['PERSONS'].'</th>
                            <th class="text-center" style="padding:15px;">'.$texts['TOTAL'].'</th>
                        </tr>';
                        foreach($result_activity as $activity){
                            $html .=
                            '<tr>
                                <td style="padding:15px;">'.$activity['title'].'</td>
                                <td style="padding:15px;">'.$activity['duration'].'</td>
                                <td style="padding:15px;">'.gmstrftime(DATE_FORMAT.' '.TIME_FORMAT, $activity['date']).'</td>
                                <td style="padding:15px;">
                                    '.($activity['adults']+$activity['children']).' '.getAltText($texts['PERSON'], $texts['PERSONS'], ($activity['adults']+$activity['children'])).': ';
                                    if($activity['adults'] > 0) $html .= $activity['adults'].' '.getAltText($texts['ADULT'], $texts['ADULTS'], $activity['adults']).' ';
                                    if($activity['children'] > 0) $html .= $activity['children'].' '.getAltText($texts['CHILD'], $texts['CHILDREN'], $activity['children']).' ';
                                    $html .= '
                                </td>
                                <td class="text-right" width="15%" style="padding:15px;">'.formatPrice($activity['amount']*CURRENCY_RATE).'</td>
                            </tr>';
                        }
                        $html .= '
                    </table>';
                }
                $html .= '
                <table class="table table-responsive table-bordered" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">';
               
                    if(ENABLE_TOURIST_TAX == 1 && $row['tourist_tax'] > 0){
                        $html .= '
                        <tr>
                            <th class="text-right" style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['TOURIST_TAX'].'</th>
                            <td class="text-right" style="padding:15px;">'.formatPrice($row['tourist_tax']*CURRENCY_RATE).'</td>
                        </tr>';
                    }
                    
                    if(isset($row['discount']) && $row['discount'] > 0){
                        $html .= '
                        <tr>
                            <th class="text-right" style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['DISCOUNT'].'</th>
                            <td class="text-right" style="padding:15px;">- '.formatPrice($row['discount']*CURRENCY_RATE).'</td>
                        </tr>';
                    }
         
                    $result_tax = $db->query('SELECT * FROM pm_booking_tax WHERE id_booking = '.$row['id']);
                    if($result_tax !== false && $db->last_row_count() > 0){
                        foreach($result_tax as $tax){
                          
                            $html .= '
                            <tr>
                                <th class="text-right" style="padding:15px;text-align: left;text-transform: uppercase;">'.$tax['name'].'</th>
                                <td class="text-right" style="padding:15px;">'.formatPrice($tax['amount']*CURRENCY_RATE).'</td>
                            </tr>';
                        }
                    }
                    
                    $html .= '
                    <tr>
                        <th class="text-right" style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['TOTAL'].' ('.$texts['INCL_TAX'].')</th>
                        <td class="text-right" width="15%" style="padding:15px;"><b>'.formatPrice($row['total']*CURRENCY_RATE).'</b></td>
                    </tr>';
                    
                    if(ENABLE_DOWN_PAYMENT == 1 && $row['down_payment'] > 0){
                        $html .= '
                        <tr>
                            <th class="text-right" style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['DOWN_PAYMENT'].' ('.$texts['INCL_TAX'].')</th>
                            <td class="text-right" width="15%" style="padding:15px;"><b>'.formatPrice($row['down_payment']*CURRENCY_RATE).'</b></td>
                        </tr>';
                    }
                    $html .= '
                </table>';
                    
                $html .= '<p><strong>'.$texts['PAYMENT'].'</strong><p>';
                
                $html .= '<p>'.$texts['PAYMENT_METHOD'].' : '.$row['payment_option'].'<br>';
                $html .= $texts['STATUS'].': ';
                switch($row['status']){
                    case 1: $html .= '<span class="label label-primary">'.$texts['AWAITING'].'</span>'; break;
                    case 2: $html .= '<span class="label label-danger">'.$texts['CANCELLED'].'</span>'; break;
                    case 3: $html .= '<span class="label label-danger">'.$texts['REJECTED_PAYMENT'].'</span>'; break;
                    case 4: $html .= '<span class="label label-success">'.$texts['PAYED'].'</span>'; break;
                    default: $html .= '<span class="label label-primary">'.$texts['AWAITING'].'</span>'; break;
                }
                $html .= '<br>';
                
                $result_payment = $db->query('SELECT * FROM pm_booking_payment WHERE id_booking = '.$row['id']);
				if($result_payment !== false && $db->last_row_count() > 0){
					$html .= '
					<table class="table table-responsive table-bordered" style="width:100%;border-bottom:1px solid #ccc;padding:0 0 20px;margin:0 0 20px;">
						<tr>
							<th style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['DATE'].'</th>
							<th style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['PAYMENT_METHOD'].'</th>
							<th class="text-center" style="padding:15px;text-align: left;text-transform: uppercase;">'.$texts['AMOUNT'].'</th>
						</tr>';
						foreach($result_payment as $payment){
							$html .=
							'<tr>
								<td style="padding:15px;">'.gmstrftime(DATE_FORMAT.' '.TIME_FORMAT, $payment['date']).'</td>
								<td style="padding:15px;">'.$payment['method'].'</td>
								<td class="text-right" width="15%" style="padding:15px;">'.formatPrice($payment['amount']*CURRENCY_RATE).'</td>
							</tr>';
						}
						$html .= '
					</table>';
				}
				
                if($row['status'] == 4){
                    $html .= $texts['PAYMENT_DATE'].' : '.gmstrftime(DATE_FORMAT.' '.TIME_FORMAT, $row['payment_date']).'<br>';
                    if(!empty($row['down_payment'])) $html .= $texts['DOWN_PAYMENT'].' : '.formatPrice($row['down_payment']*CURRENCY_RATE).'<br>';
                    if(!empty($row['trans'])) $html .= $texts['NUM_TRANSACTION'].' : '.$row['trans'];
                }
                $html .= '</p>';
            } 
 				
				
				
		if(sendMail($row['email'], $row['firstname'].' '.$row['lastname'], 'Booking Summary', $html) !== false){
		   //echo 1;
		   $response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array('message'=>'Email Sent!'));
		   displayOutput($response);
		}else{
			 //echo 0;
		   $response=array('status'=>array('error_code'=>1,'message'=>'Email Not Sent!'));
		   displayOutput($response);
		}
				
				
  
}
?>