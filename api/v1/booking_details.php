<?php
/**
 * Script called on Booking Details API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');


$data=json_decode(file_get_contents('php://input'), true);

$bookingId = $data['bookingId'];

if(empty($bookingId)){
  $response=array('status'=>array('error_code'=>1,'message'=>'Booking Id is required'));
  displayOutput($response);
}

$result_exists = $db->query('SELECT a.id as booking_id, FROM_UNIXTIME(a.add_date,"%a, %D %M %Y") as booking_date, FROM_UNIXTIME((a.from_date+86400),"%a, %D %M %Y") as checkin_date, FROM_UNIXTIME((a.to_date+86400),"%a, %D %M %Y") as checkout_date, CONCAT(a.firstname, " ",a.lastname) as booking_name, a.email, a.mobile, a.total as total_amount, b.title as hotel_name, b.address as hotel_address, c.method as payment_method FROM pm_booking a INNER JOIN pm_hotel b ON a.id_hotel = b.id INNER JOIN pm_booking_payment c ON a.id = c.id_booking WHERE a.id = "'.$bookingId.'"');

$result_bookingRooms = $db->query('SELECT a.adults, a.children, (a.adults+a.children) as total_persons, b.title as room_name FROM pm_booking_room a INNER JOIN pm_room b ON a.id_room = b.id WHERE a.id_booking = "'.$bookingId.'"');

if($result_exists !== false && $db->last_row_count() > 0){
	
	$rowExist = $result_exists->fetch();
	$rowRbookings = $result_bookingRooms->fetchAll();
	
	$result = array();
	$result = $rowExist;
	$result['rooms'] = $rowRbookings;
	
	$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array('booking_details'=>$result));
	displayOutput($response);
	
} else {
	$response=array('status'=>array('error_code'=>1,'message'=>'No Booking Found!'));
	displayOutput($response);
}