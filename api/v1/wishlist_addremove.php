<?php
/**
 * Script called on Add or Remove Wishlist API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');

$data=json_decode(file_get_contents('php://input'), true);

$userId = $data['userId'];
$hotelId = $data['hotelId'];


if(empty($userId)){
  $response=array('status'=>array('error_code'=>1,'message'=>'User Id is required'));
  displayOutput($response);
}

if(empty($hotelId)){
  $response=array('status'=>array('error_code'=>1,'message'=>'Hotel Id is required'));
  displayOutput($response);
}
  

//$result_exists = $db->query('SELECT IF ((SELECT COUNT(*) FROM pm_wishlist WHERE hotel_id = '.$hotelId.' AND user_id = '.$userId.'), "TRUE", "FALSE")  as is_fav FROM pm_wishlist');

$result_exists = $db->query('SELECT * FROM pm_wishlist WHERE hotel_id = "'.$hotelId.'" AND user_id = "'.$userId.'"');

if($result_exists !== false && $db->last_row_count() > 0){	
	
	$db->query('DELETE FROM pm_wishlist WHERE hotel_id = "'.$hotelId.'" AND user_id = "'.$userId.'"');	
	
	$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array("message" => "Hotel Successfully Removed from Wishlist"));
	displayOutput($response);	
	
} else {
	
	$db->query('INSERT INTO pm_wishlist (hotel_id, user_id, date) VALUES ("'.$hotelId.'", "'.$userId.'", unix_timestamp())');
	
	$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response'=>array("message" => "Hotel Successfully Added to Wishlist"));
	displayOutput($response);
	
}
displayOutput($response);


?>