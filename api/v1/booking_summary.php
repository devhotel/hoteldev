<?php
/**
 * Script called on Booking Summary API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');


$data=json_decode(file_get_contents('php://input'), true);

$hotelId = $data['hotelId'];
$rooms = $data['rooms'];

$roomString = array_column($rooms, 'roomId');
$roomArr = implode(',', $roomString);


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


$result_accoType = $db->query('SELECT id_accommodation FROM pm_hotel WHERE id = "'.$hotelId.'"');
$rowAcco = $result_accoType->fetch();

$result_accoTax = $db->query('SELECT id as tax_id, name as taxName, value as taxValue FROM pm_tax WHERE FIND_IN_SET("'.$rowAcco['id_accommodation'].'",id_accommodation) > 0');
$rowAccotax = $result_accoTax->fetch();

$taxName = $rowAccotax['taxName'];
$taxValue = $rowAccotax['taxValue'];


$result_taxSlab = $db->query('SELECT * FROM pm_tax_slab WHERE id_tax = "'.$rowAccotax['tax_id'].'"');
$rowtaxSlab = $result_taxSlab->fetchAll();


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

	$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'taxName'=>$taxName, 'taxValue'=>$taxValue, 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
	displayOutput($response);

} else {
	
	$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array('cart_amount'=>array('subTotal'=>number_format((float)$sum, 2, '.', ''), 'taxTotal'=>number_format((float)$taxAmount, 2, '.', ''), 'cartTotal'=>number_format((float)$totalAmount, 2, '.', ''))));
	displayOutput($response);
	
}