<?php
/**
 * Script called on Get Feedback Params API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');


$data=json_decode(file_get_contents('php://input'), true);

$hotelId = $data['hotelId'];

if(empty($hotelId)){
  $response=array('status'=>array('error_code'=>1,'message'=>'Hotel Id is required'));
  displayOutput($response);
}

$result_params = $db->query('SELECT id as attribute_id, params as attribute_name FROM pm_feedback_params WHERE id_hotel = "'.$hotelId.'"');

if($result_params !== false && $db->last_row_count() > 0){
	
	$rowParams = $result_params->fetchAll();
	
	$response=array('status'=>array('error_code'=>0,'message'=>'Success'), 'response' => array('comment_attribute'=>$rowParams));
	displayOutput($response);
	
} else {
	$response=array('status'=>array('error_code'=>1,'message'=>'Comment Attributes Not Found!'));
	displayOutput($response);
}