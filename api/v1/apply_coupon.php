<?php
/**
 * Script called on Apply Coupon API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');


$data=json_decode(file_get_contents('php://input'), true);

$couponCode = $data['couponCode'];
$hotelId = $data['hotelId'];
$rooms = $data['rooms'];

//print_r($rooms);

$roomString = array_column($rooms, 'roomId');
$roomArr = implode(',', $roomString);

//$roomQty = array_column($rooms, 'roomQty');
//$roomPrice = array_column($rooms, 'roomPrice');




if(empty($couponCode)){
  $response=array('status'=>array('error_code'=>1,'message'=>'Coupon Code is required'));
  displayOutput($response);
}

if(empty($hotelId)){
  $response=array('status'=>array('error_code'=>1,'message'=>'Hotel Id is required'));
  displayOutput($response);
}

if(in_array('', array_column($rooms, 'roomId'))) {
	$response=array('status'=>array('error_code'=>1,'message'=>'Room Id is required in Rooms'));
  	displayOutput($response);
}

if(in_array('', array_column($rooms, 'roomQty'))) {
	$response=array('status'=>array('error_code'=>1,'message'=>'Room Qty is required in Rooms'));
  	displayOutput($response);
}

if(in_array('', array_column($rooms, 'roomPrice'))) {
	$response=array('status'=>array('error_code'=>1,'message'=>'Room Price is required in Rooms'));
  	displayOutput($response);
}


$result_exists = $db->query('SELECT * FROM pm_coupon WHERE code = "'.$couponCode.'" AND checked = 1');

if($result_exists !== false && $db->last_row_count() > 0){ //If Coupon Exist
	
	$row = $result_exists->fetch();
	
	if($row['id_hotel'] == $hotelId){ //Check Hotel Id Exist with the Coupon Code
		
		//room check
		
		$result_accoType = $db->query('SELECT id_accommodation FROM pm_hotel WHERE id = "'.$hotelId.'"');
		$rowAcco = $result_accoType->fetch();
		
		$result_accoTax = $db->query('SELECT id as tax_id, name as taxName, value as taxValue FROM pm_tax WHERE FIND_IN_SET("'.$rowAcco['id_accommodation'].'",id_accommodation) > 0');
		$rowAccotax = $result_accoTax->fetch();
		
		$taxName = $rowAccotax['taxName'];
		$taxValue = $rowAccotax['taxValue'];
		
		$result_taxSlab = $db->query('SELECT * FROM pm_tax_slab WHERE id_tax = "'.$rowAccotax['tax_id'].'"');
		$rowtaxSlab = $result_taxSlab->fetchAll();
		
		$getRooms = explode(',', $row['rooms']);
		$getRoomsarr = explode(',', $roomArr);
		
		$roomInter = array_intersect($getRooms, $getRoomsarr);		
		
		if($roomInter){ //if room ,coupon will applied
			
			$sum = 0;
			$subTotal = 0;
			$taxTotal = 0;
			foreach($rooms as $key=>$value){ //Get Room Subtotal				
				
				$subtotalAmount = $value['roomPrice'] * $value['roomQty'];
				
				$sum += $subtotalAmount;
			}
			
			/*if($rowtaxSlab !== false && $db->last_row_count() > 0){ //Check if Tax Slab Exist in Tax Slab Table
		
				foreach($rowtaxSlab as $rowtaxs){
					
					if($sum >= $rowtaxs['start'] && $sum <= $rowtaxs['end']){
						$taxPercentage = $rowtaxs['value'];
						$taxAmount = ($taxPercentage * $sum) / 100;
						$totalAmount = $sum + $taxAmount;
						
						break;
					}
					
				}
				
			} else {
				
				$taxPercentage = $taxValue;
				$taxAmount = ($taxPercentage * $sum) / 100;
				$totalAmount = $sum + $taxAmount;
				
			}*/
			
			if($row['discount_type'] == "rate"){ //Check Coupon Type
			
				if($rowAccotax !== false && $db->last_row_count() > 0){ //Check if Accomodation Exist in Tax Table
				
					$coupondiscountAmount = ($sum * $row['discount']) / 100;
					$couponAmount = $sum - $coupondiscountAmount;
					
					if($rowtaxSlab !== false && $db->last_row_count() > 0){ //Check if Tax Slab Exist in Tax Slab Table
		
						foreach($rowtaxSlab as $rowtaxs){
							
							if($couponAmount >= $rowtaxs['start'] && $couponAmount <= $rowtaxs['end']){
								$taxPercentage = $rowtaxs['value'];
								$taxAmount = ($taxPercentage * $couponAmount) / 100;
								$totalAmount = $couponAmount + $taxAmount;
								
								break;
							}
							
						}
						
					} else {
						
						$taxPercentage = $taxValue;
						$taxAmount = ($taxPercentage * $couponAmount) / 100;
						$totalAmount = $couponAmount + $taxAmount;
						
					}
					
					$response=array('status'=>array('error_code'=>0,'message'=>'Coupon Applied'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''),  'coupondiscountAmount'=>number_format((float)$coupondiscountAmount, 2, '.', ''), 'taxName'=>$taxName, 'taxValue'=>$taxValue, 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
					displayOutput($response);
				
				} else {
					
					$coupondiscountAmount = ($sum * $row['discount']) / 100;
					$couponAmount = $sum - $coupondiscountAmount;
					
					if($rowtaxSlab !== false && $db->last_row_count() > 0){ //Check if Tax Slab Exist in Tax Slab Table
		
						foreach($rowtaxSlab as $rowtaxs){
							
							if($couponAmount >= $rowtaxs['start'] && $couponAmount <= $rowtaxs['end']){
								$taxPercentage = $rowtaxs['value'];
								$taxAmount = ($taxPercentage * $couponAmount) / 100;
								$totalAmount = $couponAmount + $taxAmount;
								
								break;
							}
							
						}
						
					} else {
						
						$taxPercentage = $taxValue;
						$taxAmount = ($taxPercentage * $couponAmount) / 100;
						$totalAmount = $couponAmount + $taxAmount;
						
					}
					
					$response=array('status'=>array('error_code'=>0,'message'=>'Coupon Applied'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'coupondiscountAmount'=>number_format((float)$coupondiscountAmount, 2, '.', ''), 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
					displayOutput($response);
					
				}
				
			} else {
				
				if($rowAccotax !== false && $db->last_row_count() > 0){ //Check if Accomodation Exist in Tax Table
				
					$coupondiscountAmount = $row['discount'];
					$couponAmount = $sum - $coupondiscountAmount;
					
					if($rowtaxSlab !== false && $db->last_row_count() > 0){ //Check if Tax Slab Exist in Tax Slab Table
		
						foreach($rowtaxSlab as $rowtaxs){
							
							if($couponAmount >= $rowtaxs['start'] && $couponAmount <= $rowtaxs['end']){
								$taxPercentage = $rowtaxs['value'];
								$taxAmount = ($taxPercentage * $couponAmount) / 100;
								$totalAmount = $couponAmount + $taxAmount;
								
								break;
							}
							
						}
						
					} else {
						
						$taxPercentage = $taxValue;
						$taxAmount = ($taxPercentage * $couponAmount) / 100;
						$totalAmount = $couponAmount + $taxAmount;
						
					}
					
					$response=array('status'=>array('error_code'=>0,'message'=>'Coupon Applied'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'coupondiscountAmount'=>number_format((float)$coupondiscountAmount, 2, '.', ''), 'taxName'=>$taxName, 'taxValue'=>$taxValue, 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
					displayOutput($response);	
				
				} else {
					
					$coupondiscountAmount = $row['discount'];
					$couponAmount = $sum - $coupondiscountAmount;
					
					if($rowtaxSlab !== false && $db->last_row_count() > 0){ //Check if Tax Slab Exist in Tax Slab Table
		
						foreach($rowtaxSlab as $rowtaxs){
							
							if($couponAmount >= $rowtaxs['start'] && $couponAmount <= $rowtaxs['end']){
								$taxPercentage = $rowtaxs['value'];
								$taxAmount = ($taxPercentage * $couponAmount) / 100;
								$totalAmount = $couponAmount + $taxAmount;
								
								break;
							}
							
						}
						
					} else {
						
						$taxPercentage = $taxValue;
						$taxAmount = ($taxPercentage * $couponAmount) / 100;
						$totalAmount = $couponAmount + $taxAmount;
						
					}
					
					$response=array('status'=>array('error_code'=>0,'message'=>'Coupon Applied'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'coupondiscountAmount'=>number_format((float)$coupondiscountAmount, 2, '.', ''), 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
					displayOutput($response);
					
				}
				
			}
			
		} else { //coupon will not applied only Cart Total Returns
		
		
			$sum = 0;
			$subTotal = 0;
			$taxTotal = 0;
			foreach($rooms as $key=>$value){ //Get Room Subtotal				
				
				$subtotalAmount = $value['roomPrice'] * $value['roomQty'];
				
				$sum += $subtotalAmount;
			}
			
			if($rowtaxSlab !== false && $db->last_row_count() > 0){ //Check if Tax Slab Exist in Tax Slab Table
		
				foreach($rowtaxSlab as $rowtaxs){
					
					if($sum >= $rowtaxs['start'] && $sum <= $rowtaxs['end']){
						$taxPercentage = $rowtaxs['value'];
						$taxAmount = ($taxPercentage * $sum) / 100;
						$totalAmount = $sum + $taxAmount;
						
						break;
					}
					
				}
				
			} else {
				
				$taxPercentage = $taxValue;
				$taxAmount = ($taxPercentage * $sum) / 100;
				$totalAmount = $sum + $taxAmount;
				
			}
			
			if($rowAccotax !== false && $db->last_row_count() > 0){ //Check if Accomodation Exist in Tax Table
			
				$response=array('status'=>array('error_code'=>0,'message'=>'Coupon Not Applicable for Added rooms!'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'taxName'=>$taxName, 'taxValue'=>$taxValue, 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
				displayOutput($response);
			
			} else {
				
				$response=array('status'=>array('error_code'=>0,'message'=>'Coupon Not Applicable for Added rooms!'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
				displayOutput($response);
				
			}
			
		}
		
		//$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array('priceList'=>"Hotel Exist. Checking Room"));
		//displayOutput($response);
		
	} else {
		
		$result_accoType = $db->query('SELECT id_accommodation FROM pm_hotel WHERE id = "'.$hotelId.'"');
		$rowAcco = $result_accoType->fetch();
		
		$result_accoTax = $db->query('SELECT id as tax_id, name as taxName, value as taxValue FROM pm_tax WHERE FIND_IN_SET("'.$rowAcco['id_accommodation'].'",id_accommodation) > 0');
		$rowAccotax = $result_accoTax->fetch();
		
		$taxName = $rowAccotax['taxName'];
		$taxValue = $rowAccotax['taxValue'];
		
		$result_taxSlab = $db->query('SELECT * FROM pm_tax_slab WHERE id_tax = "'.$rowAccotax['tax_id'].'"');
		$rowtaxSlab = $result_taxSlab->fetchAll();
		
		$getRooms = explode(',', $row['rooms']);
		$getRoomsarr = explode(',', $roomArr);
		
		$roomInter = array_intersect($getRooms, $getRoomsarr);		
		
		if($roomInter){ //if room coupon will applied
			
			$sum = 0;
			$subTotal = 0;
			$taxTotal = 0;
			foreach($rooms as $key=>$value){ //Get Room Subtotal				
				
				$subtotalAmount = $value['roomPrice'] * $value['roomQty'];
				
				$sum += $subtotalAmount;
			}
			
			/*if($rowtaxSlab !== false && $db->last_row_count() > 0){ //Check if Tax Slab Exist in Tax Slab Table
		
				foreach($rowtaxSlab as $rowtaxs){
					
					if($sum >= $rowtaxs['start'] && $sum <= $rowtaxs['end']){
						$taxPercentage = $rowtaxs['value'];
						$taxAmount = ($taxPercentage * $sum) / 100;
						$totalAmount = $sum + $taxAmount;
						
						break;
					}
					
				}
				
			} else {
				
				$taxPercentage = $taxValue;
				$taxAmount = ($taxPercentage * $sum) / 100;
				$totalAmount = $sum + $taxAmount;
				
			}*/
			
			if($row['discount_type'] == "rate"){ //Check Coupon Type
			
				if($rowAccotax !== false && $db->last_row_count() > 0){ //Check if Accomodation Exist in Tax Table
				
					$coupondiscountAmount = ($sum * $row['discount']) / 100;
					$couponAmount = $sum - $coupondiscountAmount;
					
					if($rowtaxSlab !== false && $db->last_row_count() > 0){ //Check if Tax Slab Exist in Tax Slab Table
		
						foreach($rowtaxSlab as $rowtaxs){
							
							if($couponAmount >= $rowtaxs['start'] && $couponAmount <= $rowtaxs['end']){
								$taxPercentage = $rowtaxs['value'];
								$taxAmount = ($taxPercentage * $couponAmount) / 100;
								$totalAmount = $couponAmount + $taxAmount;
								
								break;
							}
							
						}
						
					} else {
						
						$taxPercentage = $taxValue;
						$taxAmount = ($taxPercentage * $couponAmount) / 100;
						$totalAmount = $couponAmount + $taxAmount;
						
					}
					
					$response=array('status'=>array('error_code'=>0,'message'=>'Coupon Applied'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'coupondiscountAmount'=>number_format((float)$coupondiscountAmount, 2, '.', ''), 'taxName'=>$taxName, 'taxValue'=>$taxValue, 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
					displayOutput($response);
				
				} else {
					
					$coupondiscountAmount = ($sum * $row['discount']) / 100;
					$couponAmount = $sum - $coupondiscountAmount;
					
					if($rowtaxSlab !== false && $db->last_row_count() > 0){ //Check if Tax Slab Exist in Tax Slab Table
		
						foreach($rowtaxSlab as $rowtaxs){
							
							if($couponAmount >= $rowtaxs['start'] && $couponAmount <= $rowtaxs['end']){
								$taxPercentage = $rowtaxs['value'];
								$taxAmount = ($taxPercentage * $couponAmount) / 100;
								$totalAmount = $couponAmount + $taxAmount;
								
								break;
							}
							
						}
						
					} else {
						
						$taxPercentage = $taxValue;
						$taxAmount = ($taxPercentage * $couponAmount) / 100;
						$totalAmount = $couponAmount + $taxAmount;
						
					}
					
					
					$response=array('status'=>array('error_code'=>0,'message'=>'Coupon Applied'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'coupondiscountAmount'=>number_format((float)$coupondiscountAmount, 2, '.', ''), 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
					displayOutput($response);
					
				}
				
			} else {
				
				if($rowAccotax !== false && $db->last_row_count() > 0){ //Check if Accomodation Exist in Tax Table
				
					$coupondiscountAmount = $row['discount'];
					$couponAmount = $sum - $coupondiscountAmount;
					
					if($rowtaxSlab !== false && $db->last_row_count() > 0){ //Check if Tax Slab Exist in Tax Slab Table
		
						foreach($rowtaxSlab as $rowtaxs){
							
							if($couponAmount >= $rowtaxs['start'] && $couponAmount <= $rowtaxs['end']){
								$taxPercentage = $rowtaxs['value'];
								$taxAmount = ($taxPercentage * $couponAmount) / 100;
								$totalAmount = $couponAmount + $taxAmount;
								
								break;
							}
							
						}
						
					} else {
						
						$taxPercentage = $taxValue;
						$taxAmount = ($taxPercentage * $couponAmount) / 100;
						$totalAmount = $couponAmount + $taxAmount;
						
					}
					
					$response=array('status'=>array('error_code'=>0,'message'=>'Coupon Applied'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'coupondiscountAmount'=>number_format((float)$coupondiscountAmount, 2, '.', ''), 'taxName'=>$taxName, 'taxValue'=>$taxValue, 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
					displayOutput($response);
				
				} else {
					
					$coupondiscountAmount = $row['discount'];
					$couponAmount = $sum - $coupondiscountAmount;
					
					if($rowtaxSlab !== false && $db->last_row_count() > 0){ //Check if Tax Slab Exist in Tax Slab Table
		
						foreach($rowtaxSlab as $rowtaxs){
							
							if($couponAmount >= $rowtaxs['start'] && $couponAmount <= $rowtaxs['end']){
								$taxPercentage = $rowtaxs['value'];
								$taxAmount = ($taxPercentage * $couponAmount) / 100;
								$totalAmount = $couponAmount + $taxAmount;
								
								break;
							}
							
						}
						
					} else {
						
						$taxPercentage = $taxValue;
						$taxAmount = ($taxPercentage * $couponAmount) / 100;
						$totalAmount = $couponAmount + $taxAmount;
						
					}
					
					$response=array('status'=>array('error_code'=>0,'message'=>'Coupon Applied'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'coupondiscountAmount'=>number_format((float)$coupondiscountAmount, 2, '.', ''), 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
					displayOutput($response);
					
				}
				
			}
			
		} else { //coupon will not applied only Cart Total Returns
		
		
			$sum = 0;
			$subTotal = 0;
			$taxTotal = 0;
			foreach($rooms as $key=>$value){ //Get Room Subtotal			
				
				$subtotalAmount = $value['roomPrice'] * $value['roomQty'];				
				
				$sum += $subtotalAmount;
			}
			
			if($rowtaxSlab !== false && $db->last_row_count() > 0){ //Check if Tax Slab Exist in Tax Slab Table
		
				foreach($rowtaxSlab as $rowtaxs){
					
					if($sum >= $rowtaxs['start'] && $sum <= $rowtaxs['end']){
						$taxPercentage = $rowtaxs['value'];
						$taxAmount = ($taxPercentage * $sum) / 100;
						$totalAmount = $sum + $taxAmount;
						
						break;
					}
					
				}
				
			} else {
				
				$taxPercentage = $taxValue;
				$taxAmount = ($taxPercentage * $sum) / 100;
				$totalAmount = $sum + $taxAmount;
				
			}
			
			if($rowAccotax !== false && $db->last_row_count() > 0){ //Check if Accomodation Exist in Tax Table
			
				$response=array('status'=>array('error_code'=>0,'message'=>'Coupon Not Applicable for Added rooms!'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'taxName'=>$taxName, 'taxValue'=>$taxValue, 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
				displayOutput($response);
			
			} else {
				
				$response=array('status'=>array('error_code'=>0,'message'=>'Coupon Not Applicable for Added rooms!'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
				displayOutput($response);
				
			}
			
		}		
		
		
	}
	
} else { //If Coupon Not Exist
	
	$response=array('status'=>array('error_code'=>1,'message'=>'Coupon Does not Exist!'));
	displayOutput($response);
	
}
displayOutput($response);


?>